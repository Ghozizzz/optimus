<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        Hi <?php echo $name?>,
        <br><br><br>
        Thankyou for registering to Optimus website. <br>
        To start using your account, 
        
        @if($link !== '')
          please click <a href="<?php echo $link?>">this link</a> for activate your account and start selling and buying right away.
        @else
          We will contact you soon for activate your account and start selling right away.
        @endif
        
        </br>
        <br>  
        Thank you again for your registration. If you have any questions, please let us know!<br><br><br>

        Regards,<br><br>

        Optimus<br>
        facebook: optimus<br>
        twitter: optimus<br>
        
    </body>
</html>
