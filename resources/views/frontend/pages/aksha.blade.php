@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/aksha.css') }}">
@endsection

@section('content')
<main class="aksha-page">
  <section class="aksha-hero" aria-labelledby="aksha-hero-title">
    <div class="aksha-hero-pattern" aria-hidden="true"></div>
    <div class="aksha-hero-accent aksha-hero-accent-one" aria-hidden="true"></div>
    <div class="aksha-hero-accent aksha-hero-accent-two" aria-hidden="true"></div>

    <div class="aksha-shell aksha-hero-layout">
      <div class="aksha-hero-copy">
        <div class="aksha-hero-brand">
          <img src="{{ asset('frontend/images/aksha_new_logo.png') }}" alt="AKSHA" class="aksha-hero-logo">
          <span>International School of Design &amp; Technology</span>
        </div>
        <p class="aksha-hero-label">Design your ambition. Create your advantage.</p>
        <h1 id="aksha-hero-title">Build Your Future In <span>Design &amp; Technology</span></h1>
        <p class="aksha-hero-text">Learn through ideas, digital tools and practical projects that help you build creative confidence, future-ready skills and a portfolio with purpose.</p>
        <div class="aksha-actions">
          <a class="aksha-btn aksha-btn-primary" href="#aksha-programs">Explore courses <span aria-hidden="true">→</span></a>
          <button class="aksha-btn aksha-btn-ghost open-modal" type="button">Book free counselling</button>
        </div>
      </div>

      <div class="aksha-hero-visual" aria-label="Student ready to build a future in design and technology">
        <div class="aksha-student-circle">
          <div class="aksha-circle-ring" aria-hidden="true"></div>
          <img src="{{ asset('frontend/images/aksha-hero-student.jpg') }}" alt="Design and technology student" class="aksha-student-image" fetchpriority="high">
        </div>
        <article class="aksha-achievement-card aksha-card-one">
          <span class="aksha-achievement-icon">✦</span>
          <div><strong>Creative Skills</strong><small>Design-led learning</small></div>
        </article>
        <article class="aksha-achievement-card aksha-card-two">
          <span class="aksha-achievement-icon">◇</span>
          <div><strong>Portfolio Projects</strong><small>Show what you can do</small></div>
        </article>
        <article class="aksha-achievement-card aksha-card-three">
          <span class="aksha-achievement-icon">&lt;/&gt;</span>
          <div><strong>Future Ready</strong><small>Technology with purpose</small></div>
        </article>
      </div>
    </div>
  </section>

  <section class="aksha-marquee" aria-label="AKSHA disciplines">
    <div class="aksha-marquee-track">
      <span>DESIGN THINKING</span><i></i><span>CREATIVE TECHNOLOGY</span><i></i><span>ARTIFICIAL INTELLIGENCE</span><i></i><span>DIGITAL EXPERIENCES</span><i></i>
      <span aria-hidden="true">DESIGN THINKING</span><i aria-hidden="true"></i><span aria-hidden="true">CREATIVE TECHNOLOGY</span><i aria-hidden="true"></i><span aria-hidden="true">ARTIFICIAL INTELLIGENCE</span><i aria-hidden="true"></i><span aria-hidden="true">DIGITAL EXPERIENCES</span><i aria-hidden="true"></i>
    </div>
  </section>

  <section class="aksha-section aksha-intro" id="aksha-intro">
    <div class="aksha-shell aksha-intro-grid">
      <div class="aksha-section-heading aksha-reveal">
        <p class="aksha-kicker">The AKSHA approach</p>
        <h2>Where creative instinct meets <span>technical intelligence.</span></h2>
      </div>
      <div class="aksha-intro-copy aksha-reveal">
        <p>AKSHA is designed for learners who refuse to choose between creativity and technology. Every learning path connects visual craft, problem solving and hands-on production.</p>
        <p>Students move from understanding a challenge to shaping an idea, building a working outcome and presenting it with confidence.</p>
        <a href="#aksha-journey" class="aksha-text-link">See how learning works <span>→</span></a>
      </div>
    </div>
  </section>

  <section class="aksha-section aksha-programs" id="aksha-programs">
    <div class="aksha-shell">
      <div class="aksha-section-heading aksha-section-heading-centered aksha-reveal">
        <p class="aksha-kicker">Explore our programmes</p>
        <h2>Courses built for the<br><span>creative economy.</span></h2>
        <p class="aksha-heading-copy">Choose a focused learning path and develop practical skills through projects, guided production and portfolio-led outcomes.</p>
      </div>

      <div class="aksha-course-catalogue">
        <section class="aksha-course-group aksha-reveal" aria-labelledby="creative-design-title">
          <div class="aksha-course-group-heading">
            <span>01</span>
            <div><p>Design discipline</p><h3 id="creative-design-title">Creative Design</h3></div>
          </div>
          <div class="aksha-course-grid">
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/pg-01.webp') }}" alt="Visual design artwork" loading="lazy">
                <span>Creative Design</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 12 Months</div>
                <h4>Graphic Design &amp; Visual Communication</h4>
                <p>Build strong foundations in layout, branding, typography and digital communication for modern visual media.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/pg-02.webp') }}" alt="Digital interface design landscape" loading="lazy">
                <span>Creative Design</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 10 Months</div>
                <h4>UI/UX &amp; Product Design</h4>
                <p>Learn user research, interface systems and prototyping to create thoughtful, intuitive digital experiences.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
          </div>
        </section>

        <section class="aksha-course-group aksha-reveal" aria-labelledby="animation-vfx-title">
          <div class="aksha-course-group-heading">
            <span>02</span>
            <div><p>Visual storytelling</p><h3 id="animation-vfx-title">Animation &amp; VFX</h3></div>
          </div>
          <div class="aksha-course-grid">
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/animation.webp') }}" alt="3D animation production workstation" loading="lazy">
                <span>Animation &amp; VFX</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 18 Months</div>
                <h4>3D Animation &amp; Digital Production</h4>
                <p>Explore modelling, texturing, lighting and animation through a structured, project-focused production pipeline.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/maac/bg/5bbd277e-b7f1-41c5-9d44-b34dc559d4d5.png') }}" alt="Cinematic visual effects environment" loading="lazy">
                <span>Animation &amp; VFX</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 15 Months</div>
                <h4>Visual Effects &amp; Motion Graphics</h4>
                <p>Create cinematic composites, motion sequences and visual effects using professional storytelling workflows.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
          </div>
        </section>

        <section class="aksha-course-group aksha-reveal" aria-labelledby="technology-title">
          <div class="aksha-course-group-heading">
            <span>03</span>
            <div><p>Build digital futures</p><h3 id="technology-title">Technology</h3></div>
          </div>
          <div class="aksha-course-grid">
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/maac/bg/4409f966-9a26-4e88-a325-1224e00d4b23.png') }}" alt="Artificial intelligence and future technology" loading="lazy">
                <span>Technology</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 12 Months</div>
                <h4>Artificial Intelligence &amp; Creative Tech</h4>
                <p>Understand generative tools, automation and responsible AI workflows for design, content and innovation.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/esport-video-game-r-wearing-headset-playing-online-video-game-space-shooter-championship.webp') }}" alt="Interactive game development setup" loading="lazy">
                <span>Technology</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 14 Months</div>
                <h4>Web, App &amp; Interactive Development</h4>
                <p>Turn design concepts into responsive websites, applications and engaging interactive digital products.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
          </div>
        </section>

        <section class="aksha-course-group aksha-reveal" aria-labelledby="digital-marketing-title">
          <div class="aksha-course-group-heading">
            <span>04</span>
            <div><p>Grow brands online</p><h3 id="digital-marketing-title">Digital Marketing</h3></div>
          </div>
          <div class="aksha-course-grid">
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/pg-05.webp') }}" alt="Creative digital campaign artwork" loading="lazy">
                <span>Digital Marketing</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 8 Months</div>
                <h4>Digital Marketing &amp; Brand Strategy</h4>
                <p>Plan integrated campaigns across search, social and content while learning how brands build meaningful audiences.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
            <article class="aksha-course-card">
              <div class="aksha-course-image">
                <img src="{{ asset('frontend/images/pg-03.webp') }}" alt="Visual storytelling and social content artwork" loading="lazy">
                <span>Digital Marketing</span>
              </div>
              <div class="aksha-course-content">
                <div class="aksha-course-duration"><i></i> 6 Months</div>
                <h4>Social Media &amp; Content Creation</h4>
                <p>Develop content strategy, short-form storytelling and campaign-ready creative for today’s social platforms.</p>
                <button class="aksha-course-btn open-modal" type="button">Explore course <span>→</span></button>
              </div>
            </article>
          </div>
        </section>
      </div>
    </div>
  </section>

  <section class="aksha-section aksha-lab">
    <div class="aksha-shell aksha-lab-grid">
      <div class="aksha-lab-visual aksha-reveal" aria-hidden="true">
        <div class="aksha-code-window">
          <div class="aksha-window-bar"><i></i><i></i><i></i><span>creative_process.aksha</span></div>
          <div class="aksha-code-line"><b>01</b><span>question</span><em>("what if?");</em></div>
          <div class="aksha-code-line"><b>02</b><span>observe</span><em>(people + context);</em></div>
          <div class="aksha-code-line"><b>03</b><span>prototype</span><em>(idea, rapidly);</em></div>
          <div class="aksha-code-line is-active"><b>04</b><span>create</span><em>(meaningful_future);</em></div>
          <div class="aksha-code-result">BUILD SUCCESSFUL <span>▮</span></div>
        </div>
      </div>
      <div class="aksha-lab-copy aksha-reveal">
        <p class="aksha-kicker">Inside the creative lab</p>
        <h2>Learn by making.<br><span>Improve by testing.</span></h2>
        <p>Ideas become clearer when they become tangible. AKSHA's learning rhythm is structured around exploration, practical challenges, critique and iteration.</p>
        <div class="aksha-feature-list">
          <div><strong>01</strong><span><b>Real briefs</b>Practice with meaningful, outcome-driven challenges.</span></div>
          <div><strong>02</strong><span><b>Studio feedback</b>Learn to explain decisions and refine your craft.</span></div>
          <div><strong>03</strong><span><b>Portfolio outcomes</b>Turn the process into work you can confidently present.</span></div>
        </div>
      </div>
    </div>
  </section>

  <section class="aksha-section aksha-journey" id="aksha-journey">
    <div class="aksha-shell">
      <div class="aksha-section-heading aksha-section-heading-centered aksha-reveal">
        <p class="aksha-kicker">Your learning journey</p>
        <h2>From first spark to <span>finished experience.</span></h2>
      </div>
      <div class="aksha-journey-line">
        <article class="aksha-journey-step aksha-reveal"><span>01</span><h3>Discover</h3><p>Explore tools, disciplines and the questions worth solving.</p></article>
        <article class="aksha-journey-step aksha-reveal"><span>02</span><h3>Design</h3><p>Shape ideas through research, systems and visual thinking.</p></article>
        <article class="aksha-journey-step aksha-reveal"><span>03</span><h3>Build</h3><p>Transform concepts into working, testable outcomes.</p></article>
        <article class="aksha-journey-step aksha-reveal"><span>04</span><h3>Launch</h3><p>Present your process, portfolio and point of view.</p></article>
      </div>
    </div>
  </section>

  <section class="aksha-section aksha-why" aria-labelledby="aksha-why-title">
    <div class="aksha-shell">
      <div class="aksha-section-heading aksha-section-heading-centered aksha-reveal">
        <p class="aksha-kicker">The AKSHA advantage</p>
        <h2 id="aksha-why-title">Why choose <span>AKSHA?</span></h2>
        <p class="aksha-heading-copy">A learning environment designed to turn curiosity into capability and creative ambition into career-ready work.</p>
      </div>

      <div class="aksha-why-grid">
        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">01</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M4 19.5V6.8c0-.9.7-1.6 1.6-1.6H10c1.1 0 2 .4 2.7 1.2L12 18c-.7-.7-1.7-1.1-2.7-1.1H4"/><path d="M20 19.5V6.8c0-.9-.7-1.6-1.6-1.6H14c-1.1 0-2 .4-2.7 1.2"/></svg>
          </div>
          <h3>Industry-Oriented Curriculum</h3>
          <p>Learn through current creative workflows, practical tools and skills shaped around the demands of modern digital industries.</p>
          <span class="aksha-why-line"></span>
        </article>

        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">02</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"/><path d="M5.5 20c.7-4 2.8-6 6.5-6s5.8 2 6.5 6"/><path d="m18 6 1.2 1.2L22 4.5"/></svg>
          </div>
          <h3>Experienced Mentors</h3>
          <p>Receive thoughtful direction, constructive critique and practical insight from mentors who understand creative production.</p>
          <span class="aksha-why-line"></span>
        </article>

        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">03</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M4 5h16v12H4z"/><path d="m8 21 4-4 4 4M8 10l2.5 2.5L16 7"/></svg>
          </div>
          <h3>Project-Based Learning</h3>
          <p>Move beyond passive lessons by solving briefs, prototyping ideas and creating outcomes through hands-on studio projects.</p>
          <span class="aksha-why-line"></span>
        </article>

        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">04</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 15l2.8-3 2.3 2 2.9-4 2 3M8 8h3"/></svg>
          </div>
          <h3>Portfolio Development</h3>
          <p>Document your thinking and present polished work that demonstrates your process, skills and individual creative voice.</p>
          <span class="aksha-why-line"></span>
        </article>

        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">05</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M4 8h16v11H4zM8 8V5h8v3"/><path d="M4 13h16M10 13v2h4v-2"/></svg>
          </div>
          <h3>Placement Assistance</h3>
          <p>Prepare for opportunities with portfolio guidance, professional readiness support and insight into creative career pathways.</p>
          <span class="aksha-why-line"></span>
        </article>

        <article class="aksha-why-card aksha-reveal">
          <span class="aksha-why-number">06</span>
          <div class="aksha-why-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M9 4h6l1 3 3 1v6l-3 1-1 3H9l-1-3-3-1V8l3-1z"/><circle cx="12" cy="11" r="3"/><path d="M12 18v3M9 21h6"/></svg>
          </div>
          <h3>AI-Powered Learning</h3>
          <p>Explore intelligent creative tools responsibly and learn how AI can strengthen ideation, production and problem solving.</p>
          <span class="aksha-why-line"></span>
        </article>
      </div>
    </div>
  </section>

  <section class="aksha-section aksha-cta">
    <div class="aksha-cta-grid" aria-hidden="true"></div>
    <div class="aksha-shell aksha-cta-inner aksha-reveal">
      <p class="aksha-kicker">Your next idea starts here</p>
      <h2>Ready to design<br><span>what comes next?</span></h2>
      <p>Talk to our counsellors and find the learning path that fits your creative ambition.</p>
      <div class="aksha-actions aksha-actions-centered">
        <button class="aksha-btn aksha-btn-primary open-modal" type="button">Book free counselling <span>↗</span></button>
        <a class="aksha-btn aksha-btn-ghost" href="#contact">Contact us</a>
      </div>
    </div>
  </section>
</main>
@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/aksha.js') }}"></script>
@endsection
