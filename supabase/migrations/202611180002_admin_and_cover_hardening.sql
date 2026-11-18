-- MZOUTI Files: secure moderation, direct-conversation profile lookup, private covers.
alter table public.works add column if not exists cover_path text;

-- Remove legacy permissive policy: PostgreSQL ORs permissive policies together.
drop policy if exists "works author pending or admin update" on public.works;
drop policy if exists "author edit pending or admin" on public.works;
create policy "author edit pending or admin" on public.works
for update to authenticated
using ((author_id=auth.uid() and status='pending') or public.is_admin())
with check ((author_id=auth.uid() and status='pending') or public.is_admin());

create or replace function public.get_conversation_profiles(member_ids uuid[])
returns table(id uuid, display_name text, username text, avatar_url text)
language sql stable security definer set search_path=public,auth as $$
 select p.id,p.display_name,p.username,p.avatar_url
 from public.profiles p
 where auth.uid() is not null
   and coalesce(array_length(member_ids,1),0) <= 100
   and p.id=any(member_ids)
   and exists (
     select 1 from public.direct_messages d
     where (d.sender_id=auth.uid() and d.recipient_id=p.id)
        or (d.recipient_id=auth.uid() and d.sender_id=p.id)
   );
$$;

-- Premium/payment RPCs remain disabled until payment flow is explicitly activated.
revoke execute on function public.approve_order_and_issue_code(uuid) from public, anon, authenticated;
revoke execute on function public.verify_product_access(text,text) from public, anon, authenticated;

revoke execute on function public.lookup_member_exact(text,boolean) from public, anon;
revoke execute on function public.get_conversation_profiles(uuid[]) from public, anon;
grant execute on function public.is_admin() to authenticated;
grant execute on function public.is_group_member() to authenticated;
grant execute on function public.lookup_member_exact(text,boolean) to authenticated;
grant execute on function public.get_conversation_profiles(uuid[]) to authenticated;
