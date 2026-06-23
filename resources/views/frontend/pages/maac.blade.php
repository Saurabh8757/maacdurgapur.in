@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/maac.css') }}">
<style>
    @font-face {
        font-family: 'Barber Chop';
        src: url('{{ asset('frontend/fonts/barber_chop/BarberChop.otf') }}') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&family=Montserrat:wght@400;500;700;800;900&display=swap');

    .maac-hero-heading,
    .maac-hero-heading .line1,
    .maac-hero-heading .passion, 
    .maac-hero-heading .line3 {
        font-family: 'Barber Chop', sans-serif !important;
        font-weight: 100 !important;
        letter-spacing: 2px;
    }

    .enquiry-title {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 700 !important;
        letter-spacing: 1px;
    }

    .maac-hero-subtitle {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-hero-tagline {
        font-family: 'Montserrat', sans-serif !important;
    }

    .offer-badge {
        font-family: 'Montserrat', sans-serif !important;
    }

    .offer-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .offer-highlight {
        font-family: 'Montserrat', sans-serif !important;
    }

    .offer-emi span {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-cta-heading {
        font-weight: 400 !important;
    }

    .maac-cta-text {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-cta-btn span {
        font-family: 'Montserrat', sans-serif !important;
    }

    .cta-feature-text {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-why-label {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-why-title {
        font-weight: 400 !important;
    }

    .why-card-title,
    .course-card-title,
    .location-card-title {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .why-card-text,
    .course-card-text,
    .course-card-tools,
    .location-card-text {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    .maac-courses-label {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-courses-title {
        font-weight: 400 !important;
    }

    .maac-courses-desc {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    .maac-locations-label {
        font-family: 'Comic Neue', sans-serif !important;
    }

    .maac-locations-title {
        font-weight: 400 !important;
    }

    .maac-locations-desc {
        font-family: 'Myriad Pro', 'Segoe UI', Arial, sans-serif !important;
        font-style: italic !important;
    }

    /* Reduce font weight of headers on Phone & Tablet views, excluding Hero section */
    @media (max-width: 991px) {
        section:not(.maac-hero) h1,
        section:not(.maac-hero) h2,
        section:not(.maac-hero) h3,
        section:not(.maac-hero) h4,
        section:not(.maac-hero) h5,
        section:not(.maac-hero) h6,
        section:not(.maac-hero) .enquiry-title,
        section:not(.maac-hero) [class*="-title"],
        section:not(.maac-hero) [class*="-heading"] {
            font-weight: 400 !important;
            -webkit-text-stroke: 0 !important; /* Prevents artificial bolding by browsers */
        }
    }

    /* Mobile view for Hero Heading: Premium spacing and size */
    @media (max-width: 767px) {
        .maac-hero-heading {
            font-size: 3.2rem !important;
            line-height: 1.1 !important;
            margin-bottom: 20px !important; /* Space between heading and subtitle */
            margin-top: -15px !important; /* Pull heading up slightly */
        }
        .maac-hero-heading span {
            display: block;
            margin-bottom: 2px !important; /* Very slight line gap */
        }
        .maac-hero-heading .passion {
            font-size: 4.2rem !important; /* Premium size for PASSION */
        }
    }
</style>
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

        <form action="{{ route('career_counselling') }}" method="POST" id="maacEnquiryForm" class="enquiry-form-body" novalidate>
          @csrf

          @if(!empty($formFields))
            <input type="hidden" name="brand_id" value="{{ $brand->id }}">
            @foreach($formFields as $field)
              @if($field->type === 'checkbox')
                <div class="enquiry-consent">
                  <input type="checkbox" name="{{ $field->field_name }}" value="1" id="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }}>
                  <label for="field_{{ $field->id }}">{!! nl2br(e($field->label)) !!}</label>
                  <span class="field-error {{ $field->field_name }}_error" style="display:block; margin-left:25px;"></span>
                </div>
              @else
                <div class="enquiry-field {{ $field->type === 'textarea' ? 'enquiry-field-textarea' : '' }}">
                  @php
                    $icon = 'user.png';
                    if($field->field_name == 'phone') $icon = 'phone-call.png';
                    elseif($field->field_name == 'email') $icon = 'email.png';
                    elseif($field->field_name == 'course_id') $icon = 'education.png';
                    elseif($field->field_name == 'location') $icon = 'location.png';
                    elseif($field->field_name == 'message') $icon = 'chat.png';
                  @endphp
                  <img loading="lazy" src="{{ asset('frontend/images/maac/icons/' . $icon) }}" alt="" class="enquiry-field-icon" {{ $field->type === 'textarea' ? 'style=top:25px;' : '' }}>
                  
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
                  @else
                    <input type="{{ $field->type }}" name="{{ $field->field_name }}" placeholder="{{ $field->placeholder }}" {{ $field->is_required ? 'required' : '' }}>
                  @endif
                  <span class="field-error {{ $field->field_name }}_error"></span>
                </div>
              @endif
            @endforeach
          @else
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
          @endif

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

    @if($cmsFeatures->isNotEmpty())
    <div class="maac-why-grid">
      @foreach($cmsFeatures as $feature)
      <div class="maac-why-card">
        <div class="why-card-icon-wrap">
          @if($feature->icon)
              <img loading="lazy" src="{{ asset($feature->icon->storage_key) }}" alt="{{ $feature->title }}" class="why-card-icon">
          @else
              <img loading="lazy" src="{{ asset('frontend/images/maac/icons/education.png') }}" alt="{{ $feature->title }}" class="why-card-icon">
          @endif
        </div>
        <h3 class="why-card-title">{!! nl2br(e($feature->title)) !!}</h3>
        <p class="why-card-text">{!! nl2br(e($feature->description)) !!}</p>
      </div>
      @endforeach
    </div>
    @else
    <div class="maac-empty-state" style="text-align: center; padding: 50px 20px; background: rgba(0,0,0,0.5); border-radius: 12px; max-width: 800px; margin: 0 auto;">
      <h3 style="color: #fff; font-size: 1.5rem; margin-bottom: 15px;">More Features Coming Soon</h3>
      <p style="color: #ccc;">We are currently updating our feature highlights. Please check back later.</p>
    </div>
    @endif
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

    @if($cmsCourses->isNotEmpty())
    <div class="swiper maac-courses-swiper">
      <div class="swiper-wrapper">
        @foreach($cmsCourses as $course)
        <div class="swiper-slide maac-course-card">
          @if($course->thumbnail)
          <div class="course-card-icon-wrap">
            <img loading="lazy" src="{{ asset('storage/' . $course->thumbnail->storage_key) }}" alt="{{ $course->title }}" class="course-card-icon">
          </div>
          @endif
          <h3 class="course-card-title">{!! nl2br(e($course->title)) !!}</h3>
          <p class="course-card-text">{!! nl2br(e($course->description)) !!}</p>
          @if($course->tools_covered && is_array($course->tools_covered) && count($course->tools_covered) > 0)
          <p class="course-card-tools">Tools covered: {{ implode(' · ', $course->tools_covered) }}</p>
          @endif
        </div>
        @endforeach
      </div>
      
      <!-- Swiper Pagination -->
      <div class="swiper-pagination mt-4"></div>
      
      <!-- Custom Swiper Navigation -->
      <div class="swiper-btn-next"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></div>
      <div class="swiper-btn-prev"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg></div>
    </div>
    @else
    <div class="maac-empty-state" style="text-align: center; padding: 50px 20px; background: rgba(0,0,0,0.5); border-radius: 12px;">
      <h3 style="color: #fff; font-size: 1.5rem; margin-bottom: 15px;">New Courses Coming Soon</h3>
      <p style="color: #ccc;">We are currently updating our course curriculum. Please check back later.</p>
    </div>
    @endif
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
