@extends('frontend.layout.app')

@section('meta_title', 'AKSHA Durgapur – Coding, UI/UX, Digital Marketing & Programming Courses')
@section('meta_description', 'AKSHA Durgapur offers comprehensive courses in Coding, Programming, UI/UX Design, and Digital Marketing. Empower your tech career with expert-led practical training.')
@section('canonical_url', url()->current())
@section('og_title', 'AKSHA Durgapur – Coding, UI/UX, Digital Marketing & Programming Courses')
@section('og_description', 'AKSHA Durgapur offers comprehensive courses in Coding, Programming, UI/UX Design, and Digital Marketing. Empower your tech career with expert-led practical training.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/aksha.css') }}?v={{ time() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&family=Montserrat:wght@300;400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap');

    .enquiry-title {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 300 !important;
        letter-spacing: 1px;
    }

    .aksha-title,
    .aksha-final-cta-content h2 {
        font-weight: 300 !important;
    }

    .aksha-hero-subtitle {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .aksha-hero-tagline {
        font-family: 'Montserrat', sans-serif !important;
    }

    .offer-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .offer-emi span {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .aksha-desc {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    .aksha-label {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .info-card-title,
    .module-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .info-card-text,
    .module-desc {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    .btn-text, .submit-arrow {
        font-family: 'Montserrat', sans-serif !important;
    }
</style>
@endsection

@section('content')

<!-- ===================== HERO SECTION ===================== -->
<section class="aksha-hero">
  <div class="aksha-hero-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="AKSHA Digital Marketing Academy Background" class="aksha-hero-bg-img" fetchpriority="high">
  </div>
  <div class="aksha-hero-overlay"></div>

  <div class="aksha-hero-content">
    <div class="aksha-hero-left">
      <h1 class="aksha-hero-heading">
        <span class="line1">MASTER THE</span>
        <span class="passion">DIGITAL FUTURE</span>
      </h1>
      <p class="aksha-hero-subtitle">Premium Professional Training Academy in Animation, Tech & Marketing</p>
      
      <div class="aksha-hero-tagline-wrapper">
        <p class="aksha-hero-tagline">LEARN . GROW . DOMINATE</p>
        <div class="tagline-line"></div>
      </div>

      <div class="aksha-hero-offer">
        <p class="offer-title">Right Here. Right Now.<br>Your Career Starts Today.</p>
        <div class="offer-emi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="offer-check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>AI-Powered Industry Training</span>
        </div>
      </div>
    </div>

    <!-- Enquiry Form matching Space-E-Fic structure -->
    <div class="aksha-hero-right">
      <div class="maac-enquiry-form">
        <div class="enquiry-form-header">
          <div class="enquiry-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
          </div>
          <h2 class="enquiry-title">GET FREE COUNSELLING</h2>
          <p class="enquiry-subtitle">Start your digital marketing journey with expert guidance.</p>
        </div>

        <form action="{{ route('career_counselling') }}" method="POST" id="akshaEnquiryForm" class="enquiry-form-body" novalidate>
          @csrf

          @if(!empty($formFields))
            <input type="hidden" name="brand_id" value="{{ $brand->id }}">
            @foreach($formFields as $field)
              <div class="enquiry-field {{ $field->type === 'textarea' ? 'enquiry-field-textarea' : '' }}">
                @php
                  $svg = '<svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                  if($field->field_name == 'phone') {
                    $svg = '<svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
                  } elseif($field->field_name == 'email') {
                    $svg = '<svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
                  } elseif($field->field_name == 'course_id') {
                    $svg = '<svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>';
                  } elseif($field->field_name == 'location') {
                    $svg = '<svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                  } elseif($field->type === 'textarea') {
                    $svg = '<svg class="enquiry-field-icon" style="top: 25px;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
                  }
                @endphp
                {!! $field->type !== 'checkbox' ? $svg : '' !!}
                
                @if($field->type === 'select')
                  <select name="{{ $field->field_name }}" {{ $field->is_required ? 'required' : '' }}>
                    <option value="" disabled selected hidden>{{ $field->placeholder }}</option>
                    @if($field->options)
                      @foreach(json_decode($field->options, true) as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                      @endforeach
                    @endif
                  </select>
                @elseif($field->type === 'textarea')
                  <textarea name="{{ $field->field_name }}" placeholder="{{ $field->placeholder }}" rows="3" {{ $field->is_required ? 'required' : '' }}></textarea>
                @elseif($field->type === 'checkbox')
                  </div>
                  <div class="enquiry-consent">
                    <input type="checkbox" name="{{ $field->field_name }}" value="1" id="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }}>
                    <label for="field_{{ $field->id }}">{!! nl2br(e($field->label)) !!}</label>
                    <span class="field-error {{ $field->field_name }}_error" style="display:block; margin-left:25px;"></span>
                  </div>
                  <div class="d-none"> <!-- Close the unneeded enquiry-field div for checkboxes since it uses enquiry-consent wrapper -->
                @else
                  <input type="{{ $field->type }}" name="{{ $field->field_name }}" placeholder="{{ $field->placeholder }}" {{ $field->is_required ? 'required' : '' }}>
                @endif
                @if($field->type !== 'checkbox')
                  <span class="field-error {{ $field->field_name }}_error"></span>
                @endif
              </div>
            @endforeach
          @else

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <input type="text" name="name" placeholder="Full Name *" required autocomplete="name">
            <span class="field-error name_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            <input type="tel" name="phone" placeholder="Phone Number *" required autocomplete="tel">
            <span class="field-error phone_error"></span>
          </div>

          <div class="enquiry-field">
             <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <input type="email" name="email" placeholder="E-mail Address *" required autocomplete="email">
            <span class="field-error email_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            <select name="course_id" required>
              <option value="" disabled selected hidden>Select Course *</option>
              <option value="3d-animation">Professional program in 3d animation</option>
              <option value="3d-animation-and-Vfx">Professional program in 3d animation and Vfx</option>
              <option value="programming-languages">Diploma in core programming and languages</option>
              <option value="digital-marketing">Professional program in digital marketing and Ai analytics</option>
            </select>
            <span class="field-error course_id_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <select name="location">
              <option value="" disabled selected hidden>Preferred Location</option>
              <option value="Durgapur">Durgapur</option>
              <option value="Asansol">Asansol</option>
              <option value="Bolpur">Bolpur</option>
            </select>
          </div>

          <div class="enquiry-field enquiry-field-textarea">
            <svg class="enquiry-field-icon" style="top: 25px;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            <textarea name="message" placeholder="Any specific questions?" rows="3"></textarea>
          </div>

          <div class="enquiry-consent">
            <input type="checkbox" id="aksha-consent" name="consent" value="1">
            <label for="aksha-consent">I agree to receive information about AKSHA courses<br>& updates via call, SMS & email.</label>
          </div>
          
          @endif

          <button type="submit" class="enquiry-submit">
            <span class="btn-text">START MY JOURNEY</span>
            <span class="submit-arrow">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY TECH & CREATIVE ===================== -->
<section class="aksha-info-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay"></div>
  <div class="aksha-info-inner">
    <div class="aksha-section-header">
      <p class="aksha-label">THE DIGITAL SHIFT</p>
      <h2 class="aksha-title">WHY CREATIVE & TECH SKILLS ARE THE CAREER OF THE DECADE</h2>
      <p class="aksha-desc">Let's be honest — the world has gone digital. From movies and games to software and marketing, every industry needs professionals who can design, code, and strategize. This isn't just a career — it's the backbone of the modern economy.</p>
    </div>

    <div class="aksha-info-grid">
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
        </div>
        <h3 class="info-card-title">Booming Tech Economy</h3>
        <p class="info-card-text">India's digital and creative markets are exploding. Companies are spending heavily on animation, software, and digital marketing every quarter — and they need trained professionals to manage it.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <h3 class="info-card-title">20 Lakh+ Job Openings</h3>
        <p class="info-card-text">From startups to top MNCs and gaming studios, there's massive demand for 3D animators, software developers, and performance marketers across India.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        </div>
        <h3 class="info-card-title">Work from Anywhere</h3>
        <p class="info-card-text">Tech and creative skills are location-independent. Freelance from home, work for global studios, or build your own agency — the flexibility is unmatched.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== MAJOR CAREER PROGRAMS ===================== -->
<section class="aksha-programs-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay" style="background: rgba(4,6,12,0.5);"></div>
  <div class="aksha-curriculum-inner">
    <div class="aksha-section-header center">
      <p class="aksha-label">FLAGSHIP COURSES</p>
      <h2 class="aksha-title">MAJOR CAREER PROGRAMS</h2>
      <p class="aksha-desc" style="max-width: 700px; margin: 0 auto;">Our comprehensive, industry-aligned programs are designed to transform beginners into highly sought-after professionals in animation, technology, and marketing.</p>
    </div>

    <div class="aksha-showcase-grid">
      @foreach($majorPrograms as $program)
      <div class="showcase-cinematic-card">
        <div class="cinematic-image-wrap">
          <img src="{{ $program->featuredImage ? $program->featuredImage->url : asset('frontend/images/pg-01.webp') }}" alt="{{ $program->title }}" loading="lazy">
          <div class="cinematic-glow"></div>
        </div>
        <div class="cinematic-content">
          <h3>{{ $program->title }}</h3>
          <p>{{ $program->short_description }}</p>
          @if(is_array($program->skills) && count($program->skills) > 0)
          <div class="course-skills-tags">
            @foreach($program->skills as $skill)
            <span>{{ trim($skill) }}</span>
            @endforeach
          </div>
          @endif
          @if($program->outcome)
          <div class="course-outcome">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Outcome: {{ $program->outcome }}
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===================== SUPPORTING COURSES ===================== -->
<section class="aksha-supporting-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay" style="background: rgba(4,6,12,0.5);"></div>
  <div class="aksha-curriculum-inner" style="position: relative; z-index: 2; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    <div class="aksha-section-header center">
      <p class="aksha-label">SPECIALIZATIONS</p>
      <h2 class="aksha-title">SUPPORTING COURSES & MODULES</h2>
      <p class="aksha-desc" style="max-width: 700px; margin: 0 auto;">Build niche skills or add to your existing portfolio with our specialized short-term programs across design, development, and marketing.</p>
    </div>

    <div class="swiper aksha-courses-swiper">
      <div class="swiper-wrapper">
      @php
          $fallbackImages = [
              'graphic-design' => 'frontend/images/pg-01.webp',
              'ui-ux-design' => 'frontend/images/aksha/bg/fantasy1.webp',
              'motion-graphics' => 'frontend/images/pg-03.webp',
              'video-editing' => 'frontend/images/pg-06.webp',
              'web-development' => 'frontend/images/aksha/bg/fantasy1.webp',
              'full-stack-development' => 'frontend/images/aksha/bg/fantasy1.webp',
              'python-programming' => 'frontend/images/aksha/bg/fantasy1.webp',
              'data-analytics' => 'frontend/images/aksha/bg/fantasy1.webp',
              'ai-tools-and-automation' => 'frontend/images/aksha/bg/fantasy1.webp',
              'seo-and-performance' => 'frontend/images/aksha/bg/fantasy1.webp',
              'social-media-marketing' => 'frontend/images/pg-05.webp',
              'content-and-branding' => 'frontend/images/pg-02.webp',
          ];
      @endphp
      @foreach($supportingCourses as $course)
      <div class="swiper-slide aksha-course-card">
        <div class="course-card-img-wrap">
          <img src="{{ $course->featuredImage ? $course->featuredImage->url : asset($fallbackImages[$course->slug] ?? 'frontend/images/aksha/bg/fantasy1.webp') }}" alt="{{ $course->title }}" loading="lazy">
        </div>
        <div class="course-card-content">
          <h3>{{ $course->title }}</h3>
          <p>{{ $course->short_description }}</p>
          @if(is_array($course->skills) && count($course->skills) > 0)
          <div class="course-skills-tags">
            @foreach($course->skills as $skill)
            <span>{{ trim($skill) }}</span>
            @endforeach
          </div>
          @endif
          @if($course->outcome)
          <div class="course-outcome">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ $course->outcome }}
          </div>
          @endif
        </div>
      </div>
      @endforeach

          </div>
      <!-- Swiper Pagination & Navigation -->
      <div class="swiper-pagination mt-4"></div>
      <div class="aksha-swiper-nav-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></div>
      <div class="aksha-swiper-nav-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></div>
    </div>
  </div>
</section>

<!-- ===================== AI POWERED ADVANTAGE ===================== -->
<section class="aksha-program-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay"></div>
  <div class="aksha-program-inner">
    <div class="aksha-program-split">
      <div class="program-content">
        <p class="aksha-label">AI-POWERED ADVANTAGE</p>
        <h2 class="aksha-title">MARKETING WITH ARTIFICIAL INTELLIGENCE</h2>
        <p class="aksha-desc">AI isn't replacing marketers — it's making smart marketers unstoppable. Our curriculum integrates the latest AI tools so you graduate ready for the future of marketing.</p>
        
        <ul class="program-feature-list">
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>AI Content Creation:</b> Write ad copies, blogs, and social posts in minutes using ChatGPT & Jasper.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Smart Ad Optimization:</b> Let AI analyze and auto-optimize your campaign budgets and bidding.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Predictive Analytics:</b> Use machine learning to forecast trends and customer behavior.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Visual Generation:</b> Create stunning marketing visuals with MidJourney and DALL-E.</span>
          </li>
        </ul>
      </div>
      <div class="program-visual">
        <div class="aksha-ai-visual-card">
          <div class="ai-visual-glow"></div>
          <div class="ai-visual-content">
            <div class="ai-visual-icon">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="3"></circle><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path></svg>
            </div>
            <h3>AI-First Curriculum</h3>
            <p>Every module integrates AI tools so you learn to work smarter, not harder.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHO IS THIS FOR ===================== -->
<section class="aksha-info-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay" style="background: rgba(4,6,12,0.5);"></div>
  <div class="aksha-info-inner">
    <div class="aksha-section-header">
      <p class="aksha-label">IS THIS FOR YOU?</p>
      <h2 class="aksha-title">WHO SHOULD JOIN THIS PROGRAM</h2>
      <p class="aksha-desc">Whether you're starting fresh or looking to upskill, this program is designed for ambitious individuals who want to master the digital economy.</p>
    </div>

    <div class="aksha-info-grid">
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
        </div>
        <h3 class="info-card-title">Fresh Graduates</h3>
        <p class="info-card-text">Kickstart your career with industry-relevant skills that employers are actively looking for. No prior marketing experience needed.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </div>
        <h3 class="info-card-title">Working Professionals</h3>
        <p class="info-card-text">Upgrade your skills, transition into digital roles, or add marketing expertise to your current domain for faster career growth.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
        <h3 class="info-card-title">Business Owners</h3>
        <p class="info-card-text">Learn to run your own Google Ads, manage social media, and understand analytics — so you stop depending on expensive agencies.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        </div>
        <h3 class="info-card-title">Students & Freelancers</h3>
        <p class="info-card-text">Build a portfolio of real projects, learn to pitch clients, and start earning as a freelance digital marketer while still studying.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== PLACEMENT & CAREER OUTCOMES ===================== -->
<section class="aksha-placement-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay"></div>
  <div class="aksha-placement-inner">
    <div class="aksha-section-header center">
      <p class="aksha-label">CAREER OUTCOMES</p>
      <h2 class="aksha-title">PLACEMENT & CAREER SUPPORT</h2>
      <p class="aksha-desc" style="max-width: 700px; margin: 0 auto;">Our students don't just learn — they get placed. With dedicated career support, resume workshops, and interview preparation.</p>
    </div>

    <div class="aksha-stats-row">
      <div class="aksha-stat-card">
        <span class="stat-number">500+</span>
        <span class="stat-label">Students Placed</span>
      </div>
      <div class="aksha-stat-card">
        <span class="stat-number">₹4-8 LPA</span>
        <span class="stat-label">Average Salary Range</span>
      </div>
      <div class="aksha-stat-card">
        <span class="stat-number">150+</span>
        <span class="stat-label">Hiring Partners</span>
      </div>
      <div class="aksha-stat-card">
        <span class="stat-number">92%</span>
        <span class="stat-label">Placement Rate</span>
      </div>
    </div>

    <div class="aksha-roles-header">
      <h3 class="aksha-title" style="font-size: clamp(20px, 3vw, 28px);">ROLES OUR GRADUATES LAND</h3>
    </div>
    <div class="aksha-info-grid" style="margin-top: 30px;">
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
        </div>
        <h3 class="info-card-title">3D Animator / VFX Artist</h3>
        <p class="info-card-text">Work in top gaming studios, film production houses, and creative agencies bringing characters and scenes to life.</p>
      </div>
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
        </div>
        <h3 class="info-card-title">Software Developer</h3>
        <p class="info-card-text">Build scalable web and software applications for global tech companies, startups, and IT consultancy firms.</p>
      </div>
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
        </div>
        <h3 class="info-card-title">Digital Marketing Manager</h3>
        <p class="info-card-text">Lead brand growth, manage performance marketing budgets, and execute SEO/SMM strategies for top brands.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY CHOOSE MAAC DURGAPUR ===================== -->
<section class="aksha-info-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay" style="background: rgba(4,6,12,0.5);"></div>
  <div class="aksha-info-inner">
    <div class="aksha-section-header">
      <p class="aksha-label">THE AKSHA ADVANTAGE</p>
      <h2 class="aksha-title">WHY CHOOSE AKSHA FOR PROFESSIONAL TRAINING</h2>
      <p class="aksha-desc">We don't just teach theory — we build industry-ready professionals with hands-on training, live projects, and dedicated career support.</p>
    </div>

    <div class="aksha-info-grid">
      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
        <h3 class="info-card-title">Google & Meta Certified Trainers</h3>
        <p class="info-card-text">Learn from professionals who hold industry certifications and have managed real ad budgets of lakhs.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        </div>
        <h3 class="info-card-title">Live Project Experience</h3>
        <p class="info-card-text">Work on real campaigns for real businesses. Not simulations — actual live ad accounts and analytics dashboards.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        <h3 class="info-card-title">AI-Integrated Curriculum</h3>
        <p class="info-card-text">Every module includes AI tool training — ChatGPT, MidJourney, Jasper — so you graduate future-ready.</p>
      </div>

      <div class="aksha-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
        </div>
        <h3 class="info-card-title">Industry Certifications</h3>
        <p class="info-card-text">Prepare for Google Ads, Google Analytics, HubSpot, and Meta Blueprint certifications with dedicated prep sessions.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== LOCATIONS SECTION ===================== -->
<section class="aksha-locations-section aksha-blended-section">
  <div class="aksha-info-bg">
    <img src="{{ asset('frontend/images/aksha/bg/fantasy1.webp') }}" alt="" class="aksha-section-bg-img" loading="lazy">
  </div>
  <div class="aksha-section-overlay"></div>
  <div class="aksha-locations-inner">
    <div class="aksha-section-header center">
      <p class="aksha-label">LEARN NEAR YOU</p>
      <h2 class="aksha-title">LOCATIONS WE PROUDLY SERVE</h2>
      <p class="aksha-desc" style="max-width: 700px; margin: 0 auto;">Our professional training academy is based at MAAC Durgapur, but we welcome students from across the region with flexible batch timings.</p>
    </div>

    <div class="aksha-locations-grid">
      <div class="aksha-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Durgapur</h3>
        </div>
        <p>City Centre, Bidhannagar, Benachity, and the surrounding industrial township.</p>
      </div>

      <div class="aksha-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Asansol</h3>
        </div>
        <p>Weekend and after-work batch options designed for commuting students and professionals.</p>
      </div>

      <div class="aksha-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Bolpur</h3>
        </div>
        <p>For creative minds from the Santiniketan region looking for career-focused professional skills training.</p>
      </div>

      <div class="aksha-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Burdwan</h3>
        </div>
        <p>Students from Burdwan district benefit from our flexible scheduling and industry-aligned program.</p>
      </div>

      <div class="aksha-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Bankura</h3>
        </div>
        <p>Ambitious learners from Bankura can access world-class creative and tech training just a short trip away.</p>
      </div>
    </div>
  </div>
</section>



@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(typeof Swiper !== 'undefined') {
        new Swiper('.aksha-courses-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.aksha-swiper-nav-next',
                prevEl: '.aksha-swiper-nav-prev',
            },
            breakpoints: {
                640: { slidesPerView: 1, spaceBetween: 20 },
                768: { slidesPerView: 2, spaceBetween: 30 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            }
        });
    }
});
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="{{ asset('frontend/js/aksha.js') }}?v={{ time() }}"></script>
@endsection
