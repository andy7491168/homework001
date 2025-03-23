<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <title>comments</title>
    </head>
    <body>
    <div>
    <button class="btn btn-dark btn-sm" onclick="window.location.href='/'">Back to Campaigns</button>
       <form action="/campaign/comment/{{$id}}" method="post">
             @csrf  
            <textarea name="comments" rows="30" cols="100">  {{ $data }}</textarea><br>
           
            <button class="btn btn-primary btn-sm" type="submit">Submit</button>
       </form> 

    </div>
    </body>
</html>

