<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <style>
        /* Add custom CSS here */
        html{
            width: 100%;
            height: 100%;
            font-family: sans-serif;
        }
        body {width:100%;
            height:100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1 { font-size: 50px; color: #a50505; }
        p { font-size: 20px; color: #333; }
        img{
            width: 30%;
        }
    </style>
</head>
<body>
    <img src="{{ asset('images/login-image.png') }}" alt="">
    <h1>401 | Unauthorized</h1>
    <p>Sorry, you are not authorized to access this page.</p>

</body>
</html>
