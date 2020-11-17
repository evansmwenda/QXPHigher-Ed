<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>QXP Meeting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<!--     <div style="background:#E5E8E8 ; text-align:center; height:300px"> 
    <img src="http://sandbox.qxp-global.com/images/logo/logo.svg" width="100" alt="" style="margin-top: 80px;">
    </div> -->
   
        <h2 style=>QXP Meeting Creation was successfull</h2>
        <p>Dear {{$data['name']}},</p>
         <p>You been has invited you to a scheduled QXP Meeting</h4>

         <p>Meeting ID : {{$data['ID']}}</p>

        <p>Topic : {{$data['topic']}}</p>

        <br>
    <a href="{{$data['link']}}"><button style="background: #11BECC; border-radius: 20px; border: 1px solid transparent; padding: 8px; width: 300px; color: #fff" 
            >CLICK HERE TO JOIN</button></a>
            <br><br>
            <p>Best Regards,</p>
            <p>QXP Support Team</p>
            <br><br>
        <i style="text-align: center; margin-top:30px">This mail is send automatically please dont reply to it.</i>
  
    </body>
</html>
