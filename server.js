const express = require('express');
const app = express();
const server = require('http').createServer(app);

// get app_name from .env file
const appUrl = process.env.APP_URL || 'http://instake.test';

const io = require('socket.io')(server, {
    cors: {
      origin: appUrl,
      methods: ["GET", "POST"]
    }
  }
);


io.on('connection', (socket) => {
  console.log('Client connected');

  socket.on('send-message', (message) => {
    console.log(message);
    io.emit('new-message', message);
  });

  socket.on('disconnect', () => {
    console.log('Client disconnected');
  });
});

const port = process.env.PORT || 3000;

server.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});


