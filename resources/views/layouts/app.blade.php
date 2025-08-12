<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ config('app.name', 'CareChain') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
  />

  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
          {{ config('app.name', 'CareChain') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side -->
          <ul class="navbar-nav me-auto">
            @auth
              @if(auth()->user()->role === 'patient')
                <li class="nav-item"><a class="nav-link" href="{{ route('family.dashboard') }}">Notes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('medications.index') }}">Medications</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('appointments.index') }}">Appointments</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('medical_files.create') }}">Medical Files</a></li>
              @elseif(auth()->user()->role === 'caregiver')
                <li class="nav-item"><a class="nav-link" href="{{ route('caregiver_patients') }}">Patients</a></li>
              @endif
            @endauth
          </ul>

          <!-- Right Side -->
          <ul class="navbar-nav ms-auto">
            @guest
              <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('register_choice') }}">Register</a></li>
            @else
              <li class="nav-item d-flex align-items-center">
                <span class="nav-link">{{ Auth::user()->name }}</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="ms-2">
                  @csrf
                  <button type="submit" class="btn btn-link nav-link p-0" style="cursor: pointer;">
                    Logout
                  </button>
                </form>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    @auth
      <button id="enable-notifications" class="btn btn-primary">
        🔔
      </button>
    @endauth

    <main class="py-4">
      @yield('content')
    </main>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

  <script>
    const firebaseConfig = {
      apiKey: "AIzaSyDt9Ss-tgqw4grGWY_c5BK8_Iz94Ozf2sk",
      authDomain: "patientreminderapp-f0f34.firebaseapp.com",
      projectId: "patientreminderapp-f0f34",
      storageBucket: "patientreminderapp-f0f34.appspot.com",
      messagingSenderId: "441255433839",
      appId: "1:441255433839:web:c799db210203e009be9f9a",
      measurementId: "G-KZ07DRZK50",
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Register Service Worker
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(registration => {
          console.log('Service Worker registered with scope:', registration.scope);
          messaging.useServiceWorker(registration);
        })
        .catch(err => {
          console.error('Service Worker registration failed:', err);
        });
    }

    // Enable notifications on button click
    document.getElementById("enable-notifications")?.addEventListener("click", () => {
      Notification.requestPermission().then(permission => {
        if (permission === "granted") {
          console.log("Notification permission granted.");

          messaging.getToken({
            vapidKey: "BIYksY05pHKR-IeSu3XeIacLhrvI5I2jnl1C3KBh0ZFiTGPedm3mrNdxAPRXJ7Xi0jwgnsyXnxSNxpZ5o8vzhNo"
          }).then((currentToken) => {
            if (currentToken) {
              console.log("Device token:", currentToken);

              // Send token to server
              fetch("/save-fcm-token", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify({ token: currentToken }),
              })
              .then(res => {
                if (res.ok) {
                  alert("Notifications enabled successfully!");
                } else {
                  alert("Failed to save token on server.");
                }
              })
              .catch(() => alert("Error sending token to server."));

            } else {
              console.log("No registration token available.");
              alert("Failed to get notification token.");
            }
          }).catch((err) => {
            console.log("Error getting token:", err);
            alert("Error enabling notifications.");
          });
        } else {
          alert("Notification permission denied.");
        }
      });
    });

    // Handle incoming messages when page is in foreground
    messaging.onMessage(payload => {
      console.log('Message received. ', payload);
      // ممكن تعرض إشعار داخل الصفحة أو تعالج الرسالة
    });
  </script>
</body>
</html>
