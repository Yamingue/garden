<?php 

 $en = json_decode("{
    collapse_key : 'type_a',
    notification : {
        body : 'Body of Your Notification',
        title: 'Title of Your Notification',
    },
    data : {
        body : 'Body of Your Notification in Data',
        title: 'Title of Your Notification in Title',
        key_1 : 'Value for key_1',
        key_2 : 'Value for key_2'
    },
       to: 'eGl1all2Sfu8ERGqzzsSaH:APA91bGHVYkHic0EoGkcv630KF_hqnec4XZYUm691vowo2ZViL_lkkKdvBC1yEnROJfbLLqGZ95AzEgHuUtMgIKN719Jl0qzWM_YnQR522TNfpnQv7JiW9ZUjOgAl7_lqw8hebGyhSaE'
   }");

   printf($en);