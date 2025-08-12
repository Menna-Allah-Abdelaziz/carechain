importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

firebase.initializeApp({
  apiKey: "AIzaSyDt9Ss-tgqw4grGWY_c5BK8_Iz94Ozf2sk",
  authDomain: "patientreminderapp-f0f34.firebaseapp.com",
  projectId: "patientreminderapp-f0f34",
  storageBucket: "patientreminderapp-f0f34.appspot.com",
  messagingSenderId: "441255433839",
  appId: "1:441255433839:web:c799db210203e009be9f9a",
  measurementId: "G-KZ07DRZK50"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    // icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
