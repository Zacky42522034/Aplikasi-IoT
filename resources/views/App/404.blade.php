<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Halaman Tidak Ditemukan</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <link href="{{ asset("IoTDashboard/styles.css") }}" rel="stylesheet">
</head>
<body>
  <div class="error-card px-4 sm:px-6 md:px-8">
    <!-- Lottie Animation -->
    <div class="w-full flex justify-center order-1 md:order-2">
      <lottie-player
        src="https://lottie.host/0f275f2b-533a-4c51-a52a-d4146495212d/ZNEsWxE04s.json"
        background="transparent"
        speed="1"
        class="w-full max-w-md h-auto"
        style="max-height: 600px;"
        loop
        autoplay
      ></lottie-player>
    </div>

    <div class="error-message text-center md:text-left order-2 md:order-1 flex flex-col justify-center">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-indigo-700 mb-2 md:mb-4">404</h1>
      <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-gray-800 mb-3 md:mb-4">Oops! Halaman Menghilang</h2>
      <p class="text-base sm:text-lg text-gray-600 mb-6">
        Halaman yang Anda cari sepertinya telah berpetualang ke tempat lain atau mungkin sedang beristirahat. Mari kembali ke beranda!
      </p>
      
      <!-- Return Home Button -->
      <div>
        <a href="/dashboard" class="home-button inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-full hover:from-indigo-700 hover:to-purple-700 transition duration-300 text-sm sm:text-base">
          <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</body>
</html>