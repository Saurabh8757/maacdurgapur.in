@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/maac.css') }}">
@endsection

@section('content')
<!-- ===================== MAAC HERO SECTION ===================== -->
<section class="maac-hero">
  <div class="maac-hero-bg">
    <img src="{{ asset('frontend/images/maac/bg/03c17441-eacb-48f5-aa1a-a682e3b05d1d.png') }}" alt="MAAC Hero Background" class="maac-hero-bg-img" fetchpriority="high">
  </div>
  <div class="maac-hero-overlay"></div>

  <div class="maac-hero-content">
    <!-- Left Side: Heading + Offer -->
    <div class="maac-hero-left">
      <h1 class="maac-hero-heading">
        <span class="line1">TURN YOUR</span>
        <span class="passion">PASSION</span>
        <span class="line3">INTO A CARRER</span>
      </h1>
      <p class="maac-hero-subtitle">Industry-focused training in Animation,<br>VFX, GAMING ,UI/UX & MULTIMEDIA.</p>
      
      <div class="maac-hero-tagline-wrapper">
        <p class="maac-hero-tagline">LEARN . CREATE. SUCCEED</p>
        <div class="tagline-line"></div>
      </div>

      <div class="maac-hero-offer">
        <span class="offer-badge">LIMITED PERIOD OFFER</span>
        <p class="offer-title">Master Animation ,<br>VFX & Graphic Design</p>
        <p class="offer-highlight">SPECIAL<br>OFFER</p>
        <div class="offer-emi">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/payment.png') }}" alt="EMI" class="offer-check-icon">
          <span>EMI Facilities Available</span>
        </div>
        <img loading="lazy" src="{{ asset('frontend/images/maac/icons/charctaer.png') }}" alt="Character" class="offer-character">
      </div>
    </div>

    <!-- Right Side: Enquiry Form -->
    <div class="maac-hero-right">
      <div class="maac-enquiry-form">
        <div class="enquiry-form-header">
          <div class="enquiry-logo">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/user.png') }}" alt="Student" class="enquiry-logo-icon">
          </div>
          <h2 class="enquiry-title">STUDENT ENQUIRY FORM</h2>
          <p class="enquiry-subtitle">Fill out the form and our counselor will<br>connect you .</p>
        </div>

        <form action="{{ route('career_counselling') }}" method="POST" id="maacEnquiryForm" class="enquiry-form-body">
          @csrf

          <div class="enquiry-field">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/user.png') }}" alt="" class="enquiry-field-icon">
            <input type="text" name="name" placeholder="Full Name *" required autocomplete="name">
            <span class="field-error name_error"></span>
          </div>

          <div class="enquiry-field">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/phone-call.png') }}" alt="" class="enquiry-field-icon">
            <input type="tel" name="phone" placeholder="Phone Number*" required autocomplete="tel">
            <span class="field-error phone_error"></span>
          </div>

          <div class="enquiry-field">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/email.png') }}" alt="" class="enquiry-field-icon">
            <input type="email" name="email" placeholder="E-mail Address*" required autocomplete="email">
            <span class="field-error email_error"></span>
          </div>

          <div class="enquiry-field">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/education.png') }}" alt="" class="enquiry-field-icon">
            <select name="course_id" required>
              <option value="" disabled selected hidden>Select Course of Interest*</option>
              @if(!empty($courses))
                @foreach ($courses as $course)
                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
              @endif
            </select>
            <span class="field-error course_id_error"></span>
          </div>

          <div class="enquiry-field">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="enquiry-field-icon">
            <select name="location">
              <option value="" disabled selected hidden>Prefered Location</option>
              <option value="Durgapur">Durgapur</option>
              <option value="Burdwan">Burdwan / Bardhaman</option>
              <option value="Bolpur">Bolpur / Santiniketan</option>
              <option value="Bankura">Bankura</option>
              <option value="Asansol">Asansol / Raniganj</option>
              <option value="Purulia">Purulia</option>
              <option value="Kolkata">Kolkata</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <div class="enquiry-field enquiry-field-textarea">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/chat.png') }}" alt="" class="enquiry-field-icon" style="top: 25px;">
            <textarea name="message" placeholder="Tell Us about Your interest / query" rows="3"></textarea>
          </div>

          <div class="enquiry-consent">
            <input type="checkbox" id="maac-consent" name="consent" value="1">
            <label for="maac-consent">I agree to receive information about MAAC courses<br>& updates via call ,SMS & email.</label>
          </div>

          <button type="submit" class="enquiry-submit">
            <span class="btn-text">ENROLL NOW</span>
            <span class="submit-arrow">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CTA SECTION ===================== -->
<section class="maac-cta-section">
  <!-- Use the city image as the blurred background -->
  <div class="maac-cta-bg">
    <img src="{{ asset('frontend/images/maac/bg/4409f966-9a26-4e88-a325-1224e00d4b23.png') }}" alt="" class="maac-cta-bg-img" loading="lazy">
  </div>
  <div class="maac-cta-overlay"></div>
  
  <div class="maac-cta-card">
    <!-- Left Column: Image -->
    <div class="cta-card-image">
      <img src="{{ asset('frontend/images/maac/bg/4409f966-9a26-4e88-a325-1224e00d4b23.png') }}" alt="Cyborg City" class="cyborg-img" loading="lazy">
    </div>
    
    <!-- Middle Column: Content -->
    <div class="cta-card-content">
      <h2 class="maac-cta-heading">NOT SURE WHICH COURSE IS RIGHT FOR YOU?</h2>
      <p class="maac-cta-text">Speak to our career experts and get personalized guidance .</p>
      <button class="maac-cta-btn open-modal">
        <img loading="lazy" src="{{ asset('frontend/images/maac/icons/customer-service.png') }}" alt="" class="cta-btn-icon">
        <span>BOOK FREE COUNSELING</span>
      </button>
    </div>

    <!-- Right Column: Features -->
    <div class="cta-card-features">
      <div class="maac-cta-feature">
        <img loading="lazy" src="{{ asset('frontend/images/maac/icons/education.png') }}" alt="" class="cta-icon-img">
        <span class="cta-feature-text">100% Career Guidance</span>
      </div>
      <div class="maac-cta-feature">
        <img loading="lazy" src="{{ asset('frontend/images/maac/icons/clipboard.png') }}" alt="" class="cta-icon-img">
        <span class="cta-feature-text">Course & Fee Details</span>
      </div>
      <div class="maac-cta-feature">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cta-icon-svg"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span class="cta-feature-text">Flexible Batch Timings</span>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY CHOOSE MAAC SECTION ===================== -->
<section class="maac-why-section">
  <div class="maac-why-bg">
    <img src="{{ asset('frontend/images/maac/bg/5bbd277e-b7f1-41c5-9d44-b34dc559d4d5.png') }}" alt="" class="maac-section-bg-img" loading="lazy">
  </div>
  <div class="maac-section-overlay"></div>
  <div class="maac-why-inner">
    <div class="maac-why-header">
      <p class="maac-why-label">WHY STUDENT ACROSS WEST BENGAL CHOOSE US ?</p>
      <h2 class="maac-why-title">WHY CHOOSE MAAC DURGAPUR ?</h2>
    </div>

    <div class="maac-why-grid">
      <!-- Card 1: Industry-Aligned Curriculum -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/education.png') }}" alt="Industry Curriculum" class="why-card-icon">
        </div>
        <h3 class="why-card-title">Industry-Aligned<br>Curriculum</h3>
        <p class="why-card-text">Industry-aligned curriculum covering Maya, Blender, Unreal Engine, Houdini FX, ZBrush, and modern AI creative tools. Emphasis on real-world projects, production workflows, and production skills used by leading studios and creative professionals.</p>
      </div>

      <!-- Card 2: 100% Placement Assistance -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/placement.png') }}" alt="Placement" class="why-card-icon">
        </div>
        <h3 class="why-card-title">100% Placement<br>Assistance</h3>
        <p class="why-card-text">Dedicated Placement Cell connecting students with animation studios, gaming companies, OTT platforms and digital agencies across India. Through campus drives, portfolio reviews, and career-focused interview preparation.</p>
      </div>

      <!-- Card 3: AI-Integrated Learning -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/clipboard.png') }}" alt="AI Learning" class="why-card-icon">
        </div>
        <h3 class="why-card-title">AI-Integrated<br>Learning</h3>
        <p class="why-card-text">One of the first creative institutes in the Durgapur–Asansol–Burdwan–Bolpur– Bankura–Purulia region to integrate AI-focused curriculum for Animation, VFX, Designers, AI Video Editing, and AI powered VFX tools into every course for future-ready creative learning.</p>
      </div>

      <!-- Card 4: State-of-the-Art Labs -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/play.png') }}" alt="Art Labs" class="why-card-icon">
        </div>
        <h3 class="why-card-title">State-of-the-<br>Art Labs</h3>
        <p class="why-card-text">Train with high-performance workstations, render farms, Wacom tablets, motion capture facilities, and the latest licensed software used by leading studios and creative professionals worldwide.</p>
      </div>

      <!-- Card 5: Affordable Fees & EMI -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/payment.png') }}" alt="EMI" class="why-card-icon">
        </div>
        <h3 class="why-card-title">Affordable Fees &<br>EMI</h3>
        <p class="why-card-text">Affordable animation and VFX courses in west Bengal with industry-quality training, modern infrastructure, and easy EMI facilities for students from Kolkata, Burdwan, Bankura, Bolpur, and beyond.</p>
      </div>

      <!-- Card 6: Conveniently Located -->
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="Location" class="why-card-icon">
        </div>
        <h3 class="why-card-title">Conveniently Located</h3>
        <p class="why-card-text">Conveniently located near City Center, Durgapur with excellent connectivity from Asansol, Burdwan, Bankura, Bolpur, Bishnupur, Purulia, and Kolkata. Hostel facilities available for outstation students.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== JOB ORIENTED COURSES SECTION ===================== -->
<section class="maac-courses-section">
  <div class="maac-courses-bg">
    <img src="{{ asset('frontend/images/maac/bg/62bf8519-7019-4d4b-92df-5d9e5e2d49ef.png') }}" alt="" class="maac-section-bg-img" loading="lazy">
  </div>
  <div class="maac-section-overlay"></div>
  <div class="maac-courses-inner">
    <div class="maac-courses-header">
      <p class="maac-courses-label">EXPLORE CAREER-FOCUSED COURSES IN</p>
      <h2 class="maac-courses-title">JOB ORIENTED COURSE IN ANIMATION, VFX & DESIGN</h2>
      <p class="maac-courses-desc">Every course at MAAC Durgapur is designed to make you employment-ready from Day 1. Students from Kolkata, Burdwan, Bolpur, Bankura, Asansol, Raniganj, and Purulia enroll and go on to build successful creative careers across India.</p>
    </div>

    <div class="swiper maac-courses-swiper">
      <div class="swiper-wrapper">
        <!-- Course 1: 3D Animation Programme -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/100.png') }}" alt="3D Animation" class="course-card-icon">
          </div>
          <h3 class="course-card-title">3D-Animation<br>Programme<br>(VFX Programme)</h3>
          <p class="course-card-text">One of the most sought-after animation courses in Durgapur, designed for students from Kolkata and Burdwan looking to build a career in 3D. Covers the complete animation pipeline from modeling and texturing to rigging to cinematic storytelling and final rendering.</p>
          <p class="course-card-tools">Tools covered: Maya · Blender · 3Ds-Max · ZBrush · Substance Painter · After Effects · Nuke · Animation</p>
        </div>

        <!-- Course 2: Visual Effects -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/101.png') }}" alt="VFX" class="course-card-icon">
          </div>
          <h3 class="course-card-title">Visual Effects<br>(VFX) Programme</h3>
          <p class="course-card-text">A top-rated VFX course in Durgapur and Asansol region. Students from Kolkata, Burdwan, and Bolpur come to learn cinema-level VFX. Learn essentials of VFX, 3D assets, real-time compositing, AI-powered workflows using Houdini FX, Nuke, and After Effects.</p>
          <p class="course-card-tools">Tools covered: Houdini FX · Nuke · After Effects · Maya · Compositing</p>
        </div>

        <!-- Course 3: Gaming & Interactive Media -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/102.png') }}" alt="Gaming" class="course-card-icon">
          </div>
          <h3 class="course-card-title">Gaming &<br>Interactive Media</h3>
          <p class="course-card-text">MAAC Durgapur is a leading gaming institute covering mobile, PC and console game development for students from Burdwan For course! Focused on complete game pipeline including 3D assets, real-time workflows using Unreal Engine and AI-powered tools.</p>
          <p class="course-card-tools">Tools covered: Unreal Engine 5 · Unity · Blender · Substance Painter · AR/VR</p>
        </div>

        <!-- Course 4: Graphic Design with AI -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/after-effects (1).png') }}" alt="Graphic Design" class="course-card-icon">
          </div>
          <h3 class="course-card-title">Graphic Design<br>with AI Tools</h3>
          <p class="course-card-text">A comprehensive graphic design course covering branding, layout design, social media graphics and digital design using Photoshop, Illustrator, InDesign, Figma, Midjourney, and AI-powered creative tools.</p>
          <p class="course-card-tools">Tools covered: Photoshop · Illustrator · InDesign · Figma · Midjourney · Generative AI</p>
        </div>

        <!-- Course 5: Motion Graphics & Video Editing -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/illustrator.png') }}" alt="Motion Graphics" class="course-card-icon">
          </div>
          <h3 class="course-card-title">Motion Graphics<br>& Video Editing</h3>
          <p class="course-card-text">A leading motion graphics and video editing course in Durgapur. Students from Kolkata, Burdwan, and Asansol learn professional video production, animation sequences, explainer videos, Premiere Pro, DaVinci Resolve, and AI-powered editing.</p>
          <p class="course-card-tools">Tools covered: After Effects · Premiere Pro · DaVinci Resolve · Runway ML · AI Video Editing</p>
        </div>

        <!-- Course 6: Digital Content Creator -->
        <div class="swiper-slide maac-course-card">
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('frontend/images/maac/icons/photoshop.png') }}" alt="Content Creator" class="course-card-icon">
          </div>
          <h3 class="course-card-title">Digital Content<br>Creator Programme</h3>
          <p class="course-card-text">Become a digital content creator with skills in video production, social media strategy, short-form video, YouTube optimization, live streaming, and monetization. Includes AI-based content generation, storytelling, and generative AI creative solutions.</p>
          <p class="course-card-tools">Tools covered: Reels · YouTube · AI Content Generation · Podcast Production</p>
        </div>
      </div>
      
      <!-- Swiper Pagination -->
      <div class="swiper-pagination mt-4"></div>
      
      <!-- Custom Swiper Navigation -->
      <div class="swiper-btn-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></div>
      <div class="swiper-btn-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></div>
    </div>
  </div>
</section>

<!-- ===================== STUDENT LOCATIONS SECTION ===================== -->
<section class="maac-locations-section">
  <div class="maac-locations-bg">
    <img src="{{ asset('frontend/images/maac/bg/03c17441-eacb-48f5-aa1a-a682e3b05d1d.png') }}" alt="" class="maac-section-bg-img" loading="lazy">
  </div>
  <div class="maac-section-overlay"></div>
  <div class="maac-locations-inner">
    <div class="maac-locations-header">
      <p class="maac-locations-label">CREATIVE EDUCATION NEAR YOU</p>
      <h2 class="maac-locations-title">STUDENT ACROSS WEST BENGAL CHOOSE MAAC DURGAPUR</h2>
      <p class="maac-locations-desc">Our Students come from every corner of West Bengal to build successful careers in Animation, VFX, Gaming, Graphic Design, Multimedia & AI Creative industries. Quality education is now closer than you think.</p>
    </div>

    <div class="maac-locations-grid">
      <!-- Location 1: Burdwan -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Burdwan /<br>Bardhaman</h3>
        </div>
        <p class="location-card-text">MAAC Durgapur is a preferred destination for students from Burdwan seeking industry-focused training in Animation, VFX, Graphic Design, and Unreal Engine. Located just 45 minutes from Burdwan, the institute offers career-oriented creative education with modern tools and placement support.</p>
      </div>

      <!-- Location 2: Bolpur -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Bolpur/<br>Santiniketan</h3>
        </div>
        <p class="location-card-text">Students from Bolpur and Santiniketan choose MAAC Durgapur for industry-focused training in VFX, Motion Graphics, Graphic Design, Video Editing, and AI-powered creative courses – delivering professional training close to home.</p>
      </div>

      <!-- Location 3: Bankura -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Bankura</h3>
        </div>
        <p class="location-card-text">MAAC Durgapur is the nearest professional animation and multimedia institute for students from Bankura, offering training in Graphic Design, Video Editing, Maya, Blender, Photoshop, Unreal Engine, and industry-focused creative courses with placement support.</p>
      </div>

      <!-- Location 4: Purulia -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Purulia</h3>
        </div>
        <p class="location-card-text">Students from Purulia choose MAAC Durgapur for industry-focused training in VFX, Gaming, Motion Graphics, Video Editing, Graphic Design, and AI-powered creative courses – helping students build successful careers in the digital media industry.</p>
      </div>

      <!-- Location 5: Raniganj, Asansol -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Raniganj, Asansol</h3>
        </div>
        <p class="location-card-text">Students from Asansol and Raniganj choose MAAC Durgapur for industry-focused training in Animation, VFX, Graphic Design, and AI Creative courses with dedicated placement assistance and career-focused learning.</p>
      </div>

      <!-- Location 6: Andal, Jamuria, Kulti -->
      <div class="maac-location-card">
        <div class="location-card-header">
          <img loading="lazy" src="{{ asset('frontend/images/maac/icons/location.png') }}" alt="" class="location-pin">
          <h3 class="location-card-title">Andal, Jamuria, Kulti,<br>Barakar, Salanpur</h3>
        </div>
        <p class="location-card-text">Students from Andal, Jamuria, Kulti, Barakar, and nearby industrial regions choose MAAC Durgapur for successful, industry-focused training in Animation, VFX, Graphic Design, Gaming, and creative technologies.</p>
      </div>
    </div>
  </div>
</section>

@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/maac.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var coursesSwiper = new Swiper(".maac-courses-swiper", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      loop: true,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 150,
        modifier: 2,
        slideShadows: true,
      },
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-btn-next",
        prevEl: ".swiper-btn-prev",
      },
    });
  });
</script>
@endsection
