@extends('frontend.layout.app')

@section('meta_title', 'Space-E-Fic Durgapur – Robotics & STEM Classes for Kids')
@section('meta_description', 'Space-E-Fic Durgapur provides interactive Robotics, AI, and STEM classes for kids. Build a strong foundation in technology with our hands-on learning approach.')
@section('canonical_url', url()->current())
@section('og_title', 'Space-E-Fic Durgapur – Robotics & STEM Classes for Kids')
@section('og_description', 'Space-E-Fic Durgapur provides interactive Robotics, AI, and STEM classes for kids. Build a strong foundation in technology with our hands-on learning approach.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/space_e_fic.css') }}?v={{ time() }}">
<style>
    @font-face {
        font-family: 'Barber Chop';
        src: url('{{ asset('frontend/fonts/barber_chop/BarberChop.otf') }}') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;400;700&family=Montserrat:wght@300;400;500;700;800;900&display=swap');

    .enquiry-title {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 300 !important;
        letter-spacing: 1px;
    }

    /* Fix tagline underline positioning on mobile */
    @media (max-width: 768px) {
        .sef-hero-tagline-wrapper {
            display: inline-flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            margin: 30px auto !important;
        }
    }

    .sef-title,
    .sef-final-cta-content h2 {
        font-weight: 300 !important;
    }

    .sef-hero-subtitle {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .sef-hero-tagline {
        font-family: 'Montserrat', sans-serif !important;
    }

    .offer-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .offer-emi span {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .sef-desc {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    .sef-label {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .info-card-title,
    .course-card-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .info-card-text,
    .course-card-desc,
    .curriculum-card-text {
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
<section class="sef-hero">
  <div class="sef-hero-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s1.png') }}" alt="Space E Fic Background" class="sef-hero-bg-img" fetchpriority="high">
  </div>
  <div class="sef-hero-overlay"></div>

  <div class="sef-hero-content">
    <div class="sef-hero-left">
      <h1 class="sef-hero-heading">
        <span class="line1">BUILD YOUR</span>
        <span class="passion">FIRST ROBOT</span>
      </h1>
      <p class="sef-hero-subtitle">Robotics Classes in Durgapur for Kids (Class 3 to 8)</p>
      
      <div class="sef-hero-tagline-wrapper">
        <p class="sef-hero-tagline">LEARN . BUILD . INNOVATE</p>
        <div class="tagline-line"></div>
      </div>

      <div class="sef-hero-offer">
        <p class="offer-title">From Curious Kids to<br>Young Innovators</p>
        <div class="offer-emi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="offer-check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>Hands-on STEM Learning</span>
        </div>
      </div>
    </div>

    <!-- Enquiry Form matching MAAC structure -->
    <div class="sef-hero-right">
      <div class="maac-enquiry-form">
        <div class="enquiry-form-header">
          <div class="enquiry-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
          </div>
          <h2 class="enquiry-title">BOOK A FREE DEMO</h2>
          <p class="enquiry-subtitle">Let your child build something before you commit to anything.</p>
        </div>

        <form action="{{ route('career_counselling') }}" method="POST" id="sefEnquiryForm" class="enquiry-form-body" novalidate>
          @csrf
          <input type="hidden" name="brand_id" value="{{ $brand->id }}">

          @if(count($formFields) > 0)
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
                  <div class="d-none">
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
            <input type="text" name="name" placeholder="Parent / Child Name *" required autocomplete="name">
            <span class="field-error name_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            <input type="tel" name="phone" placeholder="Phone Number*" required autocomplete="tel">
            <span class="field-error phone_error"></span>
          </div>

          <div class="enquiry-field">
             <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <input type="email" name="email" placeholder="E-mail Address*" required autocomplete="email">
            <span class="field-error email_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            <select name="course_id" required>
              <option value="" disabled selected hidden>Select Class Level*</option>
              <option value="junior">Junior Explorer (Class 3-5)</option>
              <option value="senior">Coding Builder (Class 6-8)</option>
            </select>
            <span class="field-error course_id_error"></span>
          </div>

          <div class="enquiry-field">
            <svg class="enquiry-field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <select name="location">
              <option value="" disabled selected hidden>Prefered Location</option>
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
            <input type="checkbox" id="sef-consent" name="consent" value="1">
            <label for="sef-consent">I agree to receive information about Space E Fic courses<br>& updates via call ,SMS & email.</label>
          </div>

          @endif

          <button type="submit" class="enquiry-submit">
            <span class="btn-text">RESERVE DEMO</span>
            <span class="submit-arrow">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY ROBOTICS MATTERS ===================== -->
<section class="sef-info-section gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s2.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay"></div>
  <div class="sef-info-inner">
    <div class="sef-section-header">
      <p class="sef-label">PREPARING FOR TOMORROW</p>
      <h2 class="sef-title">WHY ROBOTICS DESERVES A PLACE IN YOUR CHILD'S ROUTINE</h2>
      <p class="sef-desc">Ask any teacher what today's classrooms are missing: a space where children are allowed to fail, fix, and try again out loud, with their hands.</p>
    </div>

    <div class="sef-info-grid">
      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        <h3 class="info-card-title">Fail, Fix, and Debug</h3>
        <p class="info-card-text">When a sensor is wired wrong, they don't get marked down—they debug it. This cycle of building, testing, and improving is incredibly powerful.</p>
      </div>

      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
        </div>
        <h3 class="info-card-title">Aligns with NEP 2020</h3>
        <p class="info-card-text">India's National Education Policy pushes strongly for experiential learning. Robotics strengthens logical reasoning and spatial thinking.</p>
      </div>

      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        </div>
        <h3 class="info-card-title">Fluent in the Future</h3>
        <p class="info-card-text">The world is shaped by automation and AI. Starting early means they'll be fluent in that world instead of catching up later.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CLASS 3-5 PROGRAM ===================== -->
<section class="sef-program-section gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s3.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay"></div>
  <div class="sef-program-inner">
    <div class="sef-program-split">
      <div class="program-content">
        <p class="sef-label">AGE MAPPED CURRICULUM</p>
        <h2 class="sef-title">JUNIOR ROBOTICS EXPLORER (CLASS 3 TO 5)</h2>
        <p class="sef-desc">This is where curiosity gets its first real toolkit. Explained the way an 8-year-old actually understands them.</p>
        
        <ul class="program-feature-list">
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Basic building blocks:</b> Motors, sensors, wheels, and circuits.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Block-based coding:</b> Drag and drop logic—no syntax errors.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Fun builds:</b> Robots that follow light or avoid walls.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Teamwork:</b> Explaining what they built and why.</span>
          </li>
        </ul>
      </div>
      <div class="program-visual">
        <img src="{{ asset('frontend/images/space_e_fic/curriculum/junior.png') }}" alt="Junior robotics student" class="cinematic-img">
      </div>
    </div>
  </div>
</section>

<!-- ===================== CLASS 6-8 PROGRAM ===================== -->
<section class="sef-program-section gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s4.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay"></div>
  <div class="sef-program-inner">
    <div class="sef-program-split reverse">
      <div class="program-content">
        <p class="sef-label">ADVANCED CURRICULUM</p>
        <h2 class="sef-title">ROBOTICS & CODING BUILDER (CLASS 6 TO 8)</h2>
        <p class="sef-desc">Students go from "following instructions" to genuinely designing solutions.</p>
        
        <ul class="program-feature-list dark-mode">
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Hands-on Microcontrollers:</b> Arduino based kits.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Structured Logic:</b> Step up into text-based programming.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Intelligent Sensors:</b> Sense light, distance, sound, and touch.</span>
          </li>
          <li>
            <div class="list-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
            <span><b>Project-based builds:</b> Intro-level AI and home-automation.</span>
          </li>
        </ul>
      </div>
      <div class="program-visual">
        <img src="{{ asset('frontend/images/space_e_fic/curriculum/senior.png') }}" alt="Advanced robotics student" class="cinematic-img">
      </div>
    </div>
  </div>
</section>

<!-- ===================== WHY PARENTS CHOOSE US ===================== -->
<section class="sef-info-section gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s6.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay" style="background: rgba(4,6,12,0.95);"></div>
  <div class="sef-info-inner">
    <div class="sef-section-header">
      <p class="sef-label">TRUST & QUALITY</p>
      <h2 class="sef-title">WHY PARENTS CHOOSE MAAC DURGAPUR</h2>
      <p class="sef-desc">We've brought the same discipline from our industry-style teaching to our kids' robotics program.</p>
    </div>

    <div class="sef-info-grid">
      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <h3 class="info-card-title">Small Batches</h3>
        <p class="info-card-text">Robotics is a hands-on subject, and our mentors make sure no child is just watching from the back.</p>
      </div>

      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </div>
        <h3 class="info-card-title">Age-Mapped Curriculum</h3>
        <p class="info-card-text">Every concept is sequenced to match exactly what a Class 3, 5, or 8 student can genuinely grasp.</p>
      </div>

      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
        </div>
        <h3 class="info-card-title">Trusted Institute</h3>
        <p class="info-card-text">MAAC Durgapur is part of an established, India-wide education brand with a physical center your family can visit.</p>
      </div>
      
      <div class="sef-info-card">
        <div class="info-card-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
        <h3 class="info-card-title">Confidence Building</h3>
        <p class="info-card-text">Children leave knowing how to think through a problem, not just how to follow a manual.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== LOCATIONS SECTION ===================== -->
<section class="sef-locations-section gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s1.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay"></div>
  <div class="sef-locations-inner">
    <div class="sef-section-header center">
      <p class="sef-label">ROBOTICS NEAR YOU</p>
      <h2 class="sef-title">PROUDLY WELCOMING YOUNG INNOVATORS</h2>
      <p class="sef-desc" style="max-width: 700px; margin: 0 auto;">Our robotics center is based in Durgapur, but our students travel in from all across the region. We've designed our batch timings to make that easy.</p>
    </div>

    <div class="sef-locations-grid">
      <div class="sef-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Durgapur</h3>
        </div>
        <p>Including City Centre, Bidhannagar, Benachity, and the surrounding industrial township.</p>
      </div>

      <div class="sef-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Asansol</h3>
        </div>
        <p>A short drive away, with weekend and after-school batch options for commuting students.</p>
      </div>

      <div class="sef-location-card">
        <div class="location-header">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <h3>Bolpur Santiniketan</h3>
        </div>
        <p>For families who value creativity and hands-on learning as much as academics.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CTA SECTION ===================== -->
<section class="sef-final-cta gsap-reveal">
  <div class="sef-info-bg">
    <img src="{{ asset('frontend/images/space_e_fic/bg/s2.png') }}" alt="" class="sef-section-bg-img" loading="lazy">
  </div>
  <div class="sef-section-overlay"></div>
  
  <div class="sef-final-cta-content">
    <h2>READY TO SEE YOUR CHILD BUILD THEIR FIRST ROBOT?</h2>
    <p>You don't need to decide anything today. The best way to know if your child will love this is to let them try it. Book a free demo class and let your child build something before you commit to anything.</p>
    <button class="sef-cta-btn" onclick="document.getElementById('sefEnquiryForm').scrollIntoView({ behavior: 'smooth', block: 'center' });">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>
      <span>BOOK FREE DEMO CLASS</span>
    </button>
  </div>
</section>

@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="{{ asset('frontend/js/space_e_fic.js') }}?v={{ time() }}"></script>
@endsection
