@php
  // Cho phép truyền biến $video và $shopUrl từ @include, có giá trị mặc định
  $video   = $video   ?? asset('videos/intro.mp4');
  $shopUrl = $shopUrl ?? (Route::has('shop') ? route('shop') : url('/'));
@endphp

<style>
  .intro-overlay{position:fixed;inset:0;z-index:9999;background:#000;display:flex;align-items:center;justify-content:center;overflow:hidden}
  .intro-overlay video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:saturate(110%) contrast(102%)}
  .intro-veil{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.15),rgba(0,0,0,.35))}
  .intro-cta-wrap{position:absolute;inset:0;display:grid;place-items:center;opacity:0;pointer-events:none;transition:opacity .6s ease}
  .intro-cta-wrap.show{opacity:1;pointer-events:auto}
  .intro-btn{padding:14px 22px;border:none;border-radius:12px;font-weight:800;cursor:pointer;background:linear-gradient(180deg,#7ee787,#4ade80);color:#0b0e14;box-shadow:0 10px 30px rgba(126,231,135,.35)}
  .intro-skip{position:absolute;top:18px;right:18px;padding:10px 14px;border-radius:10px;background:rgba(18,24,38,.6);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.12);color:#fff;font-weight:700;cursor:pointer;opacity:0;transition:opacity .3s ease}
  .intro-skip.show{opacity:1}
</style>

<div class="intro-overlay" id="introRoot" aria-label="Intro video">
  <video id="introVideo" playsinline muted preload="auto">
    <source src="{{ $video }}" type="video/mp4" />
    Trình duyệt không hỗ trợ video.
  </video>
  <div class="intro-veil" aria-hidden="true"></div>

  <button class="intro-skip" id="introSkip">Bỏ qua</button>

  <div class="intro-cta-wrap" id="introEnd">
    <a href="{{ $shopUrl }}" class="intro-btn" id="introShopNow">Shop now</a>
  </div>
</div>

<script>
  (function(){
    const SEEN_KEY = 'introSeen';     // đánh dấu đã xem trong phiên
    const SKIP_DELAY_MS = 5000;       // hiện nút Bỏ qua sau 5s
    const forceIntro = new URLSearchParams(location.search).has('intro');

    const root  = document.getElementById('introRoot');
    const video = document.getElementById('introVideo');
    const end   = document.getElementById('introEnd');
    const skip  = document.getElementById('introSkip');
    const shop  = document.getElementById('introShopNow');

    function inside(){ // vào site: ẩn overlay + đánh dấu đã xem
      sessionStorage.setItem(SEEN_KEY,'1');
      root.style.display='none';
    }

    // Nếu đã xem trong phiên và không ép ?intro=1 → bỏ qua intro
    if (sessionStorage.getItem(SEEN_KEY) && !forceIntro){
      inside();
      return;
    }

    // Phát video (muted + playsinline cho phép autoplay)
    video.addEventListener('canplay', async ()=>{ try{ await video.play(); }catch(e){} }, {once:true});
    setTimeout(()=>skip.classList.add('show'), SKIP_DELAY_MS);

    video.addEventListener('ended', ()=>{ end.classList.add('show'); });
    video.addEventListener('error', ()=>{ inside(); });

    skip.addEventListener('click', inside);
    shop.addEventListener('click', ()=>{ sessionStorage.setItem(SEEN_KEY,'1'); });
  })();
</script>
