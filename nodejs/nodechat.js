var io = require('socket.io').listen(8082,{
  'log level':1
});

usernames = {};
io.sockets.on('connection', function(socket){
    socket.emit('startup', usernames);

    socket.on('newMessage', function (data) {
        io.sockets.emit('updateChat', data);
    });
    
    socket.on('userConnected', function(data){
        var isoDateTime = new Date();
        socket.username = data.username;
        data['status'] = 'online';
        data['isoDateTime'] = isoDateTime.toISOString();
        usernames[data.username] = data;
        io.sockets.emit('newUserConnected', usernames);
    });
    
    socket.on('disconnect', function(){
        // remove the username from global usernames list
        delete usernames[socket.username];
        // update list of users in chat, client-side
        io.sockets.emit('updateUsers', usernames);
    });
});