-- MZOUTI Files V1 — apply in Supabase SQL Editor.
create extension if not exists pgcrypto;

create table if not exists public.profiles (
 id uuid primary key references auth.users(id) on delete cascade,
 email_lookup text unique,
 display_name text,
 username text unique,
 bio text default '',
 avatar_url text,
 role text not null default 'member' check (role in ('member','admin')),
 invite_count integer not null default 0 check(invite_count between 0 and 15),
 created_at timestamptz not null default now()
);
create table if not exists public.categories (
 id uuid primary key default gen_random_uuid(), name text not null unique, slug text not null unique, created_at timestamptz default now()
);
insert into public.categories(name,slug) values ('Histoires','histoires'),('Pensées','pensees'),('Livres','livres'),('Applications','applications'),('Documents','documents') on conflict do nothing;

create table if not exists public.works (
 id uuid primary key default gen_random_uuid(),
 author_id uuid not null references public.profiles(id) on delete cascade,
 title text not null, slug text not null unique, category text not null,
 description text default '', content text default '', cover_url text, file_path text,
 author_name text, status text not null default 'pending' check(status in ('pending','approved','rejected')),
 moderation_note text, created_at timestamptz not null default now(), updated_at timestamptz not null default now()
);
create table if not exists public.favorites (
 id uuid primary key default gen_random_uuid(), user_id uuid not null references public.profiles(id) on delete cascade,
 work_id uuid not null references public.works(id) on delete cascade, created_at timestamptz default now(), unique(user_id,work_id)
);
create table if not exists public.playlist_items (
 id uuid primary key default gen_random_uuid(), user_id uuid not null references public.profiles(id) on delete cascade,
 work_id uuid not null references public.works(id) on delete cascade, created_at timestamptz default now(), unique(user_id,work_id)
);
create table if not exists public.notifications (
 id uuid primary key default gen_random_uuid(), user_id uuid not null references public.profiles(id) on delete cascade,
 title text not null, body text not null, kind text default 'info', read_at timestamptz, created_at timestamptz default now()
);
create table if not exists public.direct_messages (
 id uuid primary key default gen_random_uuid(), sender_id uuid not null references public.profiles(id) on delete cascade,
 recipient_id uuid not null references public.profiles(id) on delete cascade, body text not null check(length(body) between 1 and 5000),
 created_at timestamptz not null default now(), check(sender_id <> recipient_id)
);
create table if not exists public.general_group (
 id boolean primary key default true check(id=true), is_open boolean not null default true, updated_at timestamptz default now()
);
insert into public.general_group(id,is_open) values(true,true) on conflict do nothing;
create table if not exists public.general_group_members (
 user_id uuid primary key references public.profiles(id) on delete cascade, joined_at timestamptz default now()
);
create table if not exists public.group_messages (
 id uuid primary key default gen_random_uuid(), sender_id uuid not null references public.profiles(id) on delete cascade,
 body text not null check(length(body) between 1 and 5000), created_at timestamptz not null default now()
);
create table if not exists public.invitation_rewards (
 id uuid primary key default gen_random_uuid(), inviter_id uuid not null references public.profiles(id) on delete cascade,
 milestone integer not null check(milestone in (5,10,15)), reward_code text not null unique,
 redeemed_at timestamptz, redeemed_by uuid references public.profiles(id), created_at timestamptz default now(), unique(inviter_id,milestone)
);
create table if not exists public.app_settings (
 key text primary key, value text not null
);
insert into public.app_settings(key,value) values('general_group_open','true') on conflict(key) do nothing;

create or replace function public.is_admin() returns boolean language sql stable security definer set search_path=public as $$
 select exists(select 1 from public.profiles where id=auth.uid() and role='admin');
$$;

create or replace function public.handle_new_user() returns trigger language plpgsql security definer set search_path=public,auth as $$
declare ref_id uuid; new_count integer; milestone integer; code text;
begin
 insert into public.profiles(id,email_lookup,display_name,username)
 values(new.id,lower(new.email),coalesce(new.raw_user_meta_data->>'display_name',split_part(new.email,'@',1)),null)
 on conflict(id) do nothing;
 insert into public.general_group_members(user_id) values(new.id) on conflict do nothing;
 ref_id := nullif(new.raw_user_meta_data->>'invited_by','')::uuid;
 if ref_id is not null and ref_id <> new.id and exists(select 1 from public.profiles where id=ref_id) then
   update public.profiles set invite_count=least(invite_count+1,15) where id=ref_id returning invite_count into new_count;
   if new_count in (5,10,15) then
     milestone := new_count;
     code := upper(encode(gen_random_bytes(8),'hex'));
     insert into public.invitation_rewards(inviter_id,milestone,reward_code) values(ref_id,milestone,code) on conflict do nothing;
     insert into public.notifications(user_id,title,body,kind)
       values(ref_id,'Récompense débloquée !','Vous avez atteint '||milestone||' invitations réussies. Votre code : '||code,'invitation_reward');
   end if;
 end if;
 return new;
end $$;
drop trigger if exists on_auth_user_created_mzouti on auth.users;
create trigger on_auth_user_created_mzouti after insert on auth.users for each row execute procedure public.handle_new_user();

create or replace function public.block_group_member_delete() returns trigger language plpgsql as $$
begin raise exception 'Le groupe général est permanent.'; end $$;
drop trigger if exists prevent_group_leave on public.general_group_members;
create trigger prevent_group_leave before delete on public.general_group_members for each row execute procedure public.block_group_member_delete();

alter table public.profiles enable row level security;
alter table public.works enable row level security;
alter table public.favorites enable row level security;
alter table public.playlist_items enable row level security;
alter table public.notifications enable row level security;
alter table public.direct_messages enable row level security;
alter table public.general_group enable row level security;
alter table public.general_group_members enable row level security;
alter table public.group_messages enable row level security;
alter table public.invitation_rewards enable row level security;
alter table public.app_settings enable row level security;

drop policy if exists "profiles own or admin" on public.profiles;
create policy "profiles own or admin" on public.profiles for select to authenticated using(id=auth.uid() or public.is_admin());
drop policy if exists "profile update own" on public.profiles;
create policy "profile update own" on public.profiles for update to authenticated using(id=auth.uid()) with check(id=auth.uid() and role=(select p.role from public.profiles p where p.id=auth.uid()));
-- Narrow exact-match lookup: do not expose a public member directory.
create or replace function public.lookup_member_exact(search_value text, search_by_email boolean default false)
returns table(id uuid, display_name text, username text, avatar_url text)
language sql stable security definer set search_path=public as $$
 select p.id,p.display_name,p.username,p.avatar_url
 from public.profiles p
 where auth.uid() is not null
   and p.id <> auth.uid()
   and (
     (search_by_email and lower(p.email_lookup)=lower(trim(search_value)))
     or
     (not search_by_email and lower(p.username)=lower(trim(both '@' from search_value)))
   )
 limit 1;
$$;
create or replace function public.is_group_member()
returns boolean language sql stable security definer set search_path=public as $$
 select exists(select 1 from public.general_group_members where user_id=auth.uid());
$$;

create policy "public approved works" on public.works for select using(status='approved' or author_id=auth.uid() or public.is_admin());
create policy "member submit works" on public.works for insert to authenticated with check(author_id=auth.uid() and status='pending');
create policy "author edit pending or admin" on public.works for update to authenticated using(author_id=auth.uid() or public.is_admin()) with check(author_id=auth.uid() or public.is_admin());
create policy "author or admin delete works" on public.works for delete to authenticated using(author_id=auth.uid() or public.is_admin());

create policy "favorites own" on public.favorites for all to authenticated using(user_id=auth.uid()) with check(user_id=auth.uid());
create policy "playlist own" on public.playlist_items for all to authenticated using(user_id=auth.uid()) with check(user_id=auth.uid());
create policy "notifications own" on public.notifications for select to authenticated using(user_id=auth.uid());
create policy "notification mark own" on public.notifications for update to authenticated using(user_id=auth.uid()) with check(user_id=auth.uid());
create policy "dm participants read" on public.direct_messages for select to authenticated using(sender_id=auth.uid() or recipient_id=auth.uid());
create policy "dm sender insert" on public.direct_messages for insert to authenticated with check(sender_id=auth.uid());
create policy "group status read" on public.general_group for select to authenticated using(auth.uid() is not null);
create policy "group status admin update" on public.general_group for update to authenticated using(public.is_admin()) with check(public.is_admin());
create policy "group messages read members" on public.group_messages for select to authenticated using(public.is_group_member());
create policy "group messages send when open" on public.group_messages for insert to authenticated with check(sender_id=auth.uid() and public.is_group_member() and exists(select 1 from public.general_group g where g.id=true and g.is_open=true));
create policy "admin delete group messages" on public.group_messages for delete to authenticated using(public.is_admin());
create policy "invitation rewards own read" on public.invitation_rewards for select to authenticated using(inviter_id=auth.uid());
create policy "settings read" on public.app_settings for select to authenticated using(true);
create policy "settings admin write" on public.app_settings for all to authenticated using(public.is_admin()) with check(public.is_admin());

-- Do not expose member counts or member lists. Only admins may inspect membership.
create policy "admin inspect group members" on public.general_group_members for select to authenticated using(public.is_admin());

-- Storage buckets: create avatars (public) and mzouti-private (private) in Dashboard.
-- Configure Storage policies in Dashboard; recommended:
-- avatars: authenticated users may upload/update only objects under folder auth.uid().
-- mzouti-private: authenticated users may upload only under works/<auth.uid>/; private read only by author/admin.


create or replace function public.protect_profile_role() returns trigger language plpgsql as $$
begin
 if new.role is distinct from old.role and not public.is_admin() then
   raise exception 'Modification du rôle interdite.';
 end if;
 new.username := nullif(lower(trim(both '@' from new.username)), '');
 return new;
end $$;
drop trigger if exists protect_profile_role_trigger on public.profiles;
create trigger protect_profile_role_trigger before update on public.profiles for each row execute procedure public.protect_profile_role();

create or replace function public.notify_direct_message() returns trigger language plpgsql security definer set search_path=public as $$
declare sender_label text;
begin
 select coalesce(display_name,username,'Un membre MZOUTI') into sender_label from public.profiles where id=new.sender_id;
 insert into public.notifications(user_id,title,body,kind)
 values(new.recipient_id,'Nouveau message de '||sender_label,left(new.body,180),'direct_message');
 return new;
end $$;
drop trigger if exists notify_direct_message_trigger on public.direct_messages;
create trigger notify_direct_message_trigger after insert on public.direct_messages for each row execute procedure public.notify_direct_message();

create or replace function public.notify_work_decision() returns trigger language plpgsql security definer set search_path=public as $$
begin
 if old.status='pending' and new.status in ('approved','rejected') then
   insert into public.notifications(user_id,title,body,kind)
   values(new.author_id,
     case when new.status='approved' then 'Votre œuvre est publiée' else 'Votre œuvre nécessite une modification' end,
     case when new.status='approved' then 'Votre publication « '||new.title||' » a été approuvée.'
          else coalesce(new.moderation_note,'Votre publication a été refusée.') end,
     'work_moderation');
 end if;
 return new;
end $$;
drop trigger if exists notify_work_decision_trigger on public.works;
create trigger notify_work_decision_trigger after update of status on public.works for each row execute procedure public.notify_work_decision();
