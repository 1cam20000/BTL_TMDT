@extends('themes.xylo.layouts.auth')

@section('content')

{{-- Font Việt hoá mượt--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">

<style>
  /* ===== Layout tổng ===== */
  .xylo-auth { min-height: 100vh; width: 100%; }
  .xylo-grid {
    display: grid;
    grid-template-columns: 1fr 0.7fr; /* trái | phải */
    min-height: 100vh;
  }
  @media (max-width: 992px) { .xylo-grid { grid-template-columns: 1fr; } }

  /* ===== Cột trái: nền + khung 3D ===== */
  .xylo-left{
    position: relative; overflow: hidden;
    background:
      radial-gradient(1200px 800px at 10% 10%, #111827 0%, #0b0e14 55%),
      radial-gradient(800px 600px at 90% 20%, #0e1730 0%, #0b0e14 60%);
    display: grid; place-items: center;
    padding: clamp(16px, 3vw, 32px);
  }

  .xylo-shop-title{
    font-family: "Be Vietnam Pro", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif;
    font-size: 2.4rem; font-weight: 800; text-align: center;
    letter-spacing: 2px; margin-bottom: 12px;
    background: linear-gradient(90deg, #7ee787, #4ade80, #22d3ee, #a855f7);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-size: 300% 300%;
    animation: gradient-move 6s ease infinite;
    text-shadow: 0 0 12px rgba(126,231,135,.6), 0 0 24px rgba(34,211,238,.4);
  }
  @keyframes gradient-move{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}

  /* Mô tả: đảm bảo 2 dòng với <br>, mượt font */
  .xylo-shop-desc{
    font-family: "Be Vietnam Pro", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif;
    color:#d1d5db; font-size:1.25rem; font-weight:500; line-height:1.5;
    text-align:center; max-width:720px; margin:0 auto 14px auto;
    -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
    text-rendering:optimizeLegibility; font-synthesis-weight:none;
  }
  @media (max-width: 480px){ .xylo-shop-desc{ font-size:1.1rem; max-width: 92vw; } }

  /* Stage 3D */
  .xylo-stage-wrap{ perspective: 1400px; width: 100%; }
  .xylo-stage{
    position: relative; width: min(720px, 90vw); aspect-ratio: 16/9;
    margin-inline:auto; transform-style:preserve-3d;
    transform: rotateX(12deg) rotateY(-18deg) translateZ(0);
    transition: transform .18s ease-out;
  }
  .xylo-device{
    position:absolute; inset:0; border-radius:22px;
    background: linear-gradient(135deg, #0f172a, #111827);
    border:1px solid rgba(255,255,255,.06);
    box-shadow: 0 30px 80px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.06);
    overflow:hidden;
  }
  .xylo-device .hero-media{
    position:absolute; inset:0; width:100%; height:100%;
    object-fit:cover; filter:saturate(110%) contrast(102%) brightness(100%);
    display:block;
  }
  .xylo-glass{
    position:absolute; inset:0; border-radius:inherit;
    background: linear-gradient(120deg,
      rgba(126,231,135,.1),
      rgba(126,231,135,0) 40%,
      rgba(126,231,135,.08) 70%,
      rgba(255,255,255,.05) 100%);
    mix-blend-mode:overlay; pointer-events:none;
  }

  /* Footer nhỏ bên trái */
  .xylo-left-footer{
    position:absolute; bottom:18px; left:0; right:0;
    text-align:center; color:#96a0b5; font-size:14px; user-select:none;
    font-family: "Be Vietnam Pro", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif;
  }

  /* ===== Cột phải: form ===== */
  .login-foam{
    background:#fff;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:40px 24px; width:100%;
    min-height:100vh;              /* thay vì height cố định để không bị khoảng đen */
    box-sizing:border-box;
  }
  @media (max-width: 992px){ .login-foam{ min-height:auto; } } /* mobile 1 cột */
  .login-foam .formmain{ width:100%; max-width:380px; }
  .login-foam .form-group{ margin-bottom:12px; }
  .login-foam .form-group input{
    width:100%; padding:12px 14px; border-radius:10px; border:1px solid #e5e7eb;
    font-family: "Be Vietnam Pro", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif;
  }
  .login-btn{
    width:100%; padding:12px 16px; border-radius:12px; border:0;
    background:#111827; color:#fff; font-weight:700; font-family:"Be Vietnam Pro", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, "Noto Sans", sans-serif;
  }
</style>

<div class="xylo-auth">
  <div class="xylo-grid">
    {{-- ===== LEFT: “Model 3D” nhận ảnh/video ===== --}}
    <aside class="xylo-left">
      <div class="xylo-shop-title">Luxshop</div>

      <div class="xylo-shop-desc">
        Bộ sưu tập đồng hồ sang trọng, chuẩn mực chính hãng.<br>
        Trải nghiệm dịch vụ tận tâm, nâng tầm phong cách tại Luxshop.
      </div>

      <div class="xylo-stage-wrap">
        <div class="xylo-stage" id="xyloStage">
          <div class="xylo-device">
            @if(($introType ?? 'image') === 'video')
              <video
                class="hero-media"
                id="xyloHeroVideo"
                preload="auto"
                playsinline
                muted
                loop
                @if(!empty($introPoster)) poster="{{ $introPoster }}" @endif
                src="{{ $introSrc ?? asset('assets/videos/intro.mp4') }}">
              </video>
            @else
              <img
                class="hero-media"
                alt="Intro image"
                src="{{ $introSrc ?? asset('assets/images/intro-store.png') }}">
            @endif
            <div class="xylo-glass"></div>
          </div>
        </div>
      </div>

      <div class="xylo-left-footer">@2025 luxshop no copy right.</div>
    </aside>

    {{-- ===== RIGHT: form đăng nhập ===== --}}
    <section class="login-foam">
      <div class="logo-login mb-2 mb-md-5">
        <img src="{{ asset('assets/images/logo-main.png') }}" width="200" alt="logo main">
      </div>

      <h2>Welcome Back</h2>
      <p>Sign in to continue.</p>

      <form class="formmain" method="POST" action="{{ route('customer.login') }}">
        @csrf
        <div class="form-group">
          <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
          @error('email')<small style="color:#ef4444">{{ $message }}</small>@enderror
        </div>

        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required>
          @error('password')<small style="color:#ef4444">{{ $message }}</small>@enderror
        </div>

        <button class="login-btn" type="submit">Login now</button>
      </form>

      <p class="text-center mt-3">
        Don't have Account
        <a href="{{ route('customer.register') }}">Signup</a>
        OR
        <a href="{{ route('customer.password.request') }}">Forgot Password?</a>
      </p>
    </section>
  </div>
</div>

<script>
  // ===== Hiệu ứng nghiêng 3D =====
  (function () {
    const ENABLE_TILT = true; 
    const stage = document.getElementById('xyloStage');
    if (!ENABLE_TILT || !stage) return;

    const maxTilt = 18;
    let frame;

    function tiltFromPointer(x, y, rect) {
      const px = (x - rect.left) / rect.width;
      const py = (y - rect.top) / rect.height;
      const rotY = (px - 0.5) * maxTilt * 2;
      const rotX = (py - 0.5) * -maxTilt * 2;
      return { rotX, rotY };
    }
    function applyTilt(rx, ry) {
      stage.style.transform = `rotateX(${rx.toFixed(2)}deg) rotateY(${ry.toFixed(2)}deg)`;
    }
    function resetTilt() {
      stage.style.transform = 'rotateX(12deg) rotateY(-18deg)';
    }

    stage.addEventListener('pointermove', (e) => {
      cancelAnimationFrame(frame);
      const rect = stage.getBoundingClientRect();
      const { rotX, rotY } = tiltFromPointer(e.clientX, e.clientY, rect);
      frame = requestAnimationFrame(() => applyTilt(rotX, rotY));
    });
    stage.addEventListener('pointerleave', resetTilt);

    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (mq.matches) { stage.style.transition = 'none'; }
  })();
</script>
@endsection