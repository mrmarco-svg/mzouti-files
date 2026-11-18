<?php
return [
'supabase'=>['url'=>env('SUPABASE_URL'),'publishable_key'=>env('SUPABASE_PUBLISHABLE_KEY'),'bucket'=>env('SUPABASE_STORAGE_BUCKET','mzouti-private')],
'payments'=>['recipient_name'=>env('PAYMENT_RECIPIENT_NAME',''),'moncash'=>env('PAYMENT_MONCASH_NUMBER',''),'natcash'=>env('PAYMENT_NATCASH_NUMBER',''),'currency'=>env('PAYMENT_CURRENCY','HTG')]
];