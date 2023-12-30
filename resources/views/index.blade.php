<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel='stylesheet' href='/style.css' />
</head>
<body>

<div class='chat'>
    <div class='top'>
    </div>
    
    <div class='messages'>
        @include('receive', ['message' => 'somethingf'])
    </div>
  
    <div class='bottom'>
        <form>
            <input type='text' id='message' name='message' placeholder='Enter message...' autocomplete='off'/>
            <button type='submit'></button>
        </form>
    </div>
</div>
    


<script>
    const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'us2'});
    const channel = pusher.subscribe('public');

   //receive messages
   channel.bind('chat', function(data){
        $.post("/receive", {
            _token: '{{csrf_token()}}',
            message: data.message,
        })
        .done(function(res){
            console.log(res)
            $(".messages > .message").last().after(res);
            $(document).scrollTop($(document).height()); //scroll to the bottom
        })
   }); 


   //broadcast messages
   $("form").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: "/broadcast",
            method: 'POST',
            headers: {
                'X-Socket-Id': pusher.connection.socket_id
            },
            
            data:{
                _token: '{{csrf_token()}}',
                message: $("form #message").val(),
            }
        })
        .done(function(res){
            console.log(res);
            $(".messages > .message").last().after(res);
            $("form #message").val('');
            $(document).scrollTop($(document).height());
        })
   });



</script>

</body>
</html>