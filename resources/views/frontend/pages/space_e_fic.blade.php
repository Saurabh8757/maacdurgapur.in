@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/space_e_fic.css') }}">
@endsection

@section('content')
<main class="sef-page">
  <section class="sef-hero" aria-labelledby="sef-hero-title">
    <div class="sef-stars" aria-hidden="true"></div>
    <div class="sef-hero-media" aria-hidden="true">
      <img src="{{ asset('frontend/images/esport-video-game-r-wearing-headset-playing-online-video-game-space-shooter-championship.webp') }}" alt="" fetchpriority="high">
    </div>
    <div class="sef-hero-shade" aria-hidden="true"></div>
    <div class="sef-hud-corner hud-top-left" aria-hidden="true"></div>
    <div class="sef-hud-corner hud-bottom-right" aria-hidden="true"></div>

    <div class="sef-shell sef-hero-content">
      <div class="sef-status"><i></i> Player one: ready</div>
      <p class="sef-eyebrow">Gaming • Interactive Media • Esports</p>
      <h1 id="sef-hero-title">Don’t just play<br>the future.<br><span>Build it.</span></h1>
      <p class="sef-hero-copy">Enter a creative arena where game art, real-time technology, interactive storytelling and competitive culture become practical skills.</p>
      <div class="sef-actions">
        <a class="sef-btn sef-btn-primary" href="#sef-tracks"><span>Explore the arena</span><b>→</b></a>
        <button class="sef-btn sef-btn-ghost open-modal" type="button"><span>Start your mission</span></button>
      </div>
    </div>
    <div class="sef-player-card" aria-hidden="true">
      <span>ACTIVE QUEST</span>
      <strong>CREATE THE<br>NEXT WORLD</strong>
      <div><i></i><i></i><i></i><i></i><i></i></div>
    </div>
    <a class="sef-scroll" href="#sef-mission"><span>Scroll to enter</span><i></i></a>
  </section>

  <section class="sef-ticker" aria-label="Space-E-Fic disciplines">
    <div class="sef-ticker-track">
      <span>GAME ART</span><b>+</b><span>GAME DEVELOPMENT</span><b>+</b><span>REAL-TIME 3D</span><b>+</b><span>ESPORTS</span><b>+</b><span>INTERACTIVE WORLDS</span><b>+</b>
      <span aria-hidden="true">GAME ART</span><b aria-hidden="true">+</b><span aria-hidden="true">GAME DEVELOPMENT</span><b aria-hidden="true">+</b><span aria-hidden="true">REAL-TIME 3D</span><b aria-hidden="true">+</b><span aria-hidden="true">ESPORTS</span><b aria-hidden="true">+</b><span aria-hidden="true">INTERACTIVE WORLDS</span><b aria-hidden="true">+</b>
    </div>
  </section>

  <section class="sef-section sef-mission" id="sef-mission">
    <div class="sef-shell sef-mission-grid">
      <div class="sef-section-title sef-reveal">
        <p class="sef-kicker"><span>01</span> The mission</p>
        <h2>Every great world starts with a <em>bold idea.</em></h2>
      </div>
      <div class="sef-mission-copy sef-reveal">
        <p>Space-E-Fic is a launchpad for creators who want to understand what makes games compelling—and how art, code, sound, story and player experience come together.</p>
        <p>Train through projects, collaborative challenges and production-minded workflows that move you from player to creator.</p>
      </div>
    </div>
    <div class="sef-shell sef-pillars">
      <article class="sef-pillar sef-reveal"><span>01</span><div><h3>Design</h3><p>Shape mechanics, stories and player experiences.</p></div></article>
      <article class="sef-pillar sef-reveal"><span>02</span><div><h3>Develop</h3><p>Build responsive systems and playable interactions.</p></div></article>
      <article class="sef-pillar sef-reveal"><span>03</span><div><h3>Create</h3><p>Craft characters, environments and visual worlds.</p></div></article>
      <article class="sef-pillar sef-reveal"><span>04</span><div><h3>Compete</h3><p>Understand esports, content and gaming culture.</p></div></article>
    </div>
  </section>

  <section class="sef-section sef-tracks" id="sef-tracks">
    <div class="sef-shell">
      <div class="sef-section-title sef-reveal">
        <p class="sef-kicker"><span>02</span> Choose your class</p>
        <h2>Find your path into the <em>game industry.</em></h2>
      </div>

      <div class="sef-track-layout">
        <div class="sef-track-tabs sef-reveal" role="tablist" aria-label="Space-E-Fic learning paths">
          <button class="sef-track-tab is-active" id="tab-game-art" role="tab" aria-selected="true" aria-controls="panel-game-art" data-track="game-art"><span>01</span> Game Art &amp; Worlds</button>
          <button class="sef-track-tab" id="tab-development" role="tab" aria-selected="false" aria-controls="panel-development" data-track="development"><span>02</span> Game Development</button>
          <button class="sef-track-tab" id="tab-realtime" role="tab" aria-selected="false" aria-controls="panel-realtime" data-track="realtime"><span>03</span> Real-Time 3D</button>
          <button class="sef-track-tab" id="tab-esports" role="tab" aria-selected="false" aria-controls="panel-esports" data-track="esports"><span>04</span> Esports &amp; Content</button>
        </div>

        <div class="sef-track-panels sef-reveal">
          <article class="sef-track-panel is-active" id="panel-game-art" role="tabpanel" aria-labelledby="tab-game-art" data-panel="game-art">
            <div class="sef-panel-number">01</div>
            <p class="sef-panel-label">Artist class</p>
            <h3>Create characters, environments and visual identities.</h3>
            <p>Develop visual storytelling skills from concept and modelling to texture, lighting and world composition.</p>
            <div class="sef-skill-tags"><span>Concept Art</span><span>3D Modelling</span><span>Texturing</span><span>Environment Art</span></div>
          </article>
          <article class="sef-track-panel" id="panel-development" role="tabpanel" aria-labelledby="tab-development" data-panel="development" hidden>
            <div class="sef-panel-number">02</div>
            <p class="sef-panel-label">Builder class</p>
            <h3>Turn mechanics and systems into playable experiences.</h3>
            <p>Explore game logic, prototyping, interaction systems and the production thinking behind engaging gameplay.</p>
            <div class="sef-skill-tags"><span>Game Logic</span><span>Prototyping</span><span>Level Systems</span><span>Testing</span></div>
          </article>
          <article class="sef-track-panel" id="panel-realtime" role="tabpanel" aria-labelledby="tab-realtime" data-panel="realtime" hidden>
            <div class="sef-panel-number">03</div>
            <p class="sef-panel-label">World class</p>
            <h3>Build immersive spaces that respond in real time.</h3>
            <p>Combine lighting, cameras, materials and interaction to create responsive worlds for games and immersive media.</p>
            <div class="sef-skill-tags"><span>Real-Time Lighting</span><span>Materials</span><span>World Building</span><span>Interaction</span></div>
          </article>
          <article class="sef-track-panel" id="panel-esports" role="tabpanel" aria-labelledby="tab-esports" data-panel="esports" hidden>
            <div class="sef-panel-number">04</div>
            <p class="sef-panel-label">Broadcast class</p>
            <h3>Understand the culture, content and energy around play.</h3>
            <p>Learn the foundations of gaming content, live production, community thinking and competitive event presentation.</p>
            <div class="sef-skill-tags"><span>Streaming</span><span>Content</span><span>Community</span><span>Live Production</span></div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="sef-section sef-pipeline">
    <div class="sef-shell">
      <div class="sef-section-title sef-section-title-center sef-reveal">
        <p class="sef-kicker"><span>03</span> Production pipeline</p>
        <h2>Level up through <em>making.</em></h2>
      </div>
      <div class="sef-pipeline-grid">
        <article class="sef-pipeline-card sef-reveal"><span>PHASE 01</span><div class="sef-pipeline-icon">✎</div><h3>Imagine</h3><p>Define the world, player and core experience.</p></article>
        <article class="sef-pipeline-card sef-reveal"><span>PHASE 02</span><div class="sef-pipeline-icon">⌘</div><h3>Prototype</h3><p>Test mechanics and find the fun quickly.</p></article>
        <article class="sef-pipeline-card sef-reveal"><span>PHASE 03</span><div class="sef-pipeline-icon">⬡</div><h3>Produce</h3><p>Build assets, systems and polished interactions.</p></article>
        <article class="sef-pipeline-card sef-reveal"><span>PHASE 04</span><div class="sef-pipeline-icon">▶</div><h3>Launch</h3><p>Present the work and tell its production story.</p></article>
      </div>
    </div>
  </section>

  <section class="sef-section sef-arena">
    <div class="sef-arena-image" aria-hidden="true">
      <img src="{{ asset('frontend/images/esport-video-game-r-wearing-headset-playing-online-video-game-space-shooter-championship.webp') }}" alt="" loading="lazy">
    </div>
    <div class="sef-arena-overlay" aria-hidden="true"></div>
    <div class="sef-shell sef-arena-content">
      <div class="sef-section-title sef-reveal">
        <p class="sef-kicker"><span>04</span> Project arena</p>
        <h2>Your portfolio is your <em>player profile.</em></h2>
        <p>Build work that shows how you think—not only the final screen. Develop concepts, playable prototypes, environments and presentation-ready project stories.</p>
      </div>
      <div class="sef-loadout sef-reveal">
        <p>CREATOR LOADOUT</p>
        <div><span>Visual craft</span><i style="--level:88%"></i></div>
        <div><span>Technical skill</span><i style="--level:82%"></i></div>
        <div><span>Team play</span><i style="--level:91%"></i></div>
        <div><span>Presentation</span><i style="--level:86%"></i></div>
      </div>
    </div>
  </section>

  <section class="sef-section sef-careers">
    <div class="sef-shell">
      <div class="sef-section-title sef-reveal">
        <p class="sef-kicker"><span>05</span> Career universe</p>
        <h2>Pick a role.<br><em>Build your skill tree.</em></h2>
      </div>
      <div class="sef-career-grid">
        <article class="sef-career-card sef-reveal"><span>01</span><h3>Game Artist</h3><p>Shape the visual language of characters, props and worlds.</p><b>ART / 3D / STORY</b></article>
        <article class="sef-career-card sef-reveal"><span>02</span><h3>Game Designer</h3><p>Design systems, challenges and memorable player journeys.</p><b>MECHANICS / UX / LEVELS</b></article>
        <article class="sef-career-card sef-reveal"><span>03</span><h3>Game Developer</h3><p>Build the logic and interactions that make an experience playable.</p><b>CODE / SYSTEMS / TESTING</b></article>
        <article class="sef-career-card sef-reveal"><span>04</span><h3>Gaming Creator</h3><p>Create content, communities and energy around interactive culture.</p><b>CONTENT / LIVE / COMMUNITY</b></article>
      </div>
    </div>
  </section>

  <section class="sef-section sef-final">
    <div class="sef-final-rings" aria-hidden="true"></div>
    <div class="sef-shell sef-final-inner sef-reveal">
      <p class="sef-status"><i></i> New mission available</p>
      <h2>Press start on your<br><em>creator journey.</em></h2>
      <p>Talk to our counsellors about the path that matches your interests in gaming, art and interactive technology.</p>
      <div class="sef-actions sef-actions-center">
        <button class="sef-btn sef-btn-primary open-modal" type="button"><span>Book free counselling</span><b>→</b></button>
        <a class="sef-btn sef-btn-ghost" href="#contact"><span>Contact us</span></a>
      </div>
    </div>
  </section>
</main>
@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/space_e_fic.js') }}"></script>
@endsection
