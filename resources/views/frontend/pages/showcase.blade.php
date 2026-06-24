@extends('frontend.layout.app')

@section('meta_title', 'Student Showcase – Creative Work by MAAC Durgapur Students')
@section('meta_description', 'Explore the amazing portfolios and creative works in Animation, VFX, 3D Design, and UI/UX created by the talented students of MAAC Durgapur.')
@section('canonical_url', url()->current())
@section('og_title', 'Student Showcase – Creative Work by MAAC Durgapur Students')
@section('og_description', 'Explore the amazing portfolios and creative works in Animation, VFX, 3D Design, and UI/UX created by the talented students of MAAC Durgapur.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}?v={{ time() }}">
@endsection

@section('content')
<!-- Showcase Hero -->
<section class="showcase-hero">
    <div class="showcase-hero-bg">
        <div class="hero-gradient-mesh"></div>
        <div class="hero-glass-overlay"></div>
        <div class="hero-bottom-fade"></div>
    </div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="showcase-hero-title">Student Showcase</h1>
        <p class="showcase-hero-subtitle">Witness the incredible portfolios, showreels, and award-winning projects created by our students across VFX, Gaming, and UI/UX.</p>
        
        <div class="showcase-filters">
            <button class="showcase-filter-btn active" data-filter="all">All Projects</button>
            @foreach($showcaseCategories as $category)
                <button class="showcase-filter-btn" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
            @endforeach
        </div>
    </div>
</section>

<!-- Showcase Portfolio Redesign -->
<section class="showcase-portfolio-section">
    <div class="container">
        
        <!-- ONE MASTER CARD WRAPPING EVERYTHING -->
        <div class="showcase-master-card">
            
            <!-- Top Featured Area -->
            <div class="featured-area">
                <div class="featured-header">
                    <div class="fh-icon">
                        <svg viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 30L2 24V4C2 2.89543 2.89543 2 4 2H20C21.1046 2 22 2.89543 22 4V24L12 30Z" stroke="#F59E0B" stroke-width="2"/>
                            <path d="M12 8V18" stroke="#F59E0B" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h2 class="fh-title">FEATURED STUDENT WORK</h2>
                </div>
                
                <div class="featured-content">
                    <div class="fc-image-wrap">
                        <img id="fcImage" src="" alt="Featured Project">
                    </div>
                    <div class="fc-details">
                        <h3 class="fc-title" id="fcTitle">Project Title</h3>
                        <span class="fc-category" id="fcCategory">Category</span>
                        
                        <div class="fc-student">
                            <span class="fc-crafted">Crafted by</span>
                            <span class="fc-name" id="fcStudent">"Student Name"</span>
                        </div>
                        
                        <p class="fc-desc" id="fcDesc">Description...</p>
                        
                        <div class="fc-software-wrap">
                            <span class="fc-software-label" id="fcSoftwareLabel" style="display:none;">Software Used</span>
                            <img id="fcSoftwareIcon" class="fc-software-icon" src="" alt="Software Icon" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Slider Area -->
            <div class="slider-area">
                <h3 class="sa-title">STUDENTS PROJECTS</h3>
                
                <div class="slider-container-wrap">
                    <div class="swiper-button-prev showcase-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </div>
                    
                    <div class="swiper showcase-swiper" id="showcaseSwiper">
                        <div class="swiper-wrapper" id="showcaseList">
                            @forelse($showcaseProjects as $index => $project)
                                @php
                                    $thumbUrl = $project->thumbnail ? asset(str_starts_with($project->thumbnail->storage_key, 'storage/') ? $project->thumbnail->storage_key : 'storage/' . $project->thumbnail->storage_key) : asset('frontend/images/placeholder.jpg');
                                    $iconUrl = $project->softwareIcon ? asset(str_starts_with($project->softwareIcon->storage_key, 'storage/') ? $project->softwareIcon->storage_key : 'storage/' . $project->softwareIcon->storage_key) : '';
                                    $formattedIndex = sprintf('%02d', $index + 1);
                                @endphp
                                <div class="swiper-slide showcase-slide" 
                                     data-index="{{ $formattedIndex }}"
                                     data-category="{{ $project->category->slug }}"
                                     data-title="{{ $project->title }}"
                                     data-student="{{ $project->student_name }}"
                                     data-category-name="{{ $project->category->name }}"
                                     data-desc="{{ $project->short_description }}"
                                     data-icon="{{ $iconUrl }}"
                                     data-thumb="{{ $thumbUrl }}">
                                    <img src="{{ $thumbUrl }}" alt="{{ $project->title }}">
                                    <div class="slide-overlay"></div>
                                </div>
                            @empty
                                <div class="swiper-slide showcase-slide empty">
                                    <p>No projects available right now.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="swiper-button-next showcase-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>
            </div>
            
        </div> <!-- End Master Card -->
        
    </div>
</section>
@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.showcase-filter-btn');
    
    // DOM Elements for Preview
    const fcImage = document.getElementById('fcImage');
    const fcTitle = document.getElementById('fcTitle');
    const fcCategory = document.getElementById('fcCategory');
    const fcStudent = document.getElementById('fcStudent');
    const fcDesc = document.getElementById('fcDesc');
    const fcSoftwareIcon = document.getElementById('fcSoftwareIcon');
    const fcSoftwareLabel = document.getElementById('fcSoftwareLabel');
    const fcDetails = document.querySelector('.fc-details');
    
    let isAnimating = false;
    let swiper = null;
    let filteredSlides = [];
    let allSlidesHTML = document.getElementById('showcaseList').innerHTML;
    
    function initSwiper() {
        if(swiper) { swiper.destroy(true, true); }
        swiper = new Swiper('.showcase-swiper', {
            slidesPerView: 3,
            spaceBetween: 20,
            centeredSlides: true,
            loop: true,
            slideToClickedSlide: true,
            speed: 600,
            grabCursor: true,
            navigation: {
                nextEl: '.showcase-next',
                prevEl: '.showcase-prev',
            },
            breakpoints: {
                320: { slidesPerView: 1.2, spaceBetween: 10 },
                640: { slidesPerView: 2, spaceBetween: 15 },
                1024: { slidesPerView: 3, spaceBetween: 30 }
            },
            on: {
                slideChangeTransitionStart: function () {
                    const activeSlide = this.slides[this.activeIndex];
                    if(activeSlide) selectProject(activeSlide);
                }
            }
        });
    }

    function selectProject(item) {
        if (isAnimating) return;
        isAnimating = true;

        const title = item.getAttribute('data-title');
        const student = item.getAttribute('data-student');
        const categoryName = item.getAttribute('data-category-name');
        const desc = item.getAttribute('data-desc');
        const iconUrl = item.getAttribute('data-icon');
        const thumbUrl = item.getAttribute('data-thumb');
        
        const tl = gsap.timeline({
            onComplete: () => { isAnimating = false; }
        });
        
        tl.to([fcImage, fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareIcon, fcSoftwareLabel], { 
            opacity: 0, y: 10, duration: 0.3, ease: 'power2.in', stagger: 0.02
        })
        .call(() => {
            fcTitle.textContent = title;
            fcStudent.textContent = `"${student}"`;
            fcCategory.textContent = categoryName;
            fcDesc.textContent = desc;
            fcImage.src = thumbUrl;
            
            if (iconUrl) {
                fcSoftwareIcon.src = iconUrl;
                fcSoftwareIcon.style.display = 'block';
                fcSoftwareLabel.style.display = 'block';
            } else {
                fcSoftwareIcon.style.display = 'none';
                fcSoftwareLabel.style.display = 'none';
            }
        })
        .to(fcImage, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' })
        .to([fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareLabel, fcSoftwareIcon], { 
            opacity: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.05
        }, "-=0.4");
    }

    // Initialize
    initSwiper();
    
    const initialSlide = document.querySelector('.showcase-slide');
    if (initialSlide) {
        fcTitle.textContent = initialSlide.getAttribute('data-title');
        fcStudent.textContent = `"${initialSlide.getAttribute('data-student')}"`;
        fcCategory.textContent = initialSlide.getAttribute('data-category-name');
        fcDesc.textContent = initialSlide.getAttribute('data-desc');
        fcImage.src = initialSlide.getAttribute('data-thumb');
        
        const iconUrl = initialSlide.getAttribute('data-icon');
        if (iconUrl) {
            fcSoftwareIcon.src = iconUrl;
            fcSoftwareIcon.style.display = 'block';
            fcSoftwareLabel.style.display = 'block';
        } else {
            fcSoftwareIcon.style.display = 'none';
            fcSoftwareLabel.style.display = 'none';
        }
        
        gsap.from(fcImage, { opacity: 0, scale: 0.95, duration: 1, ease: 'power3.out' });
        gsap.from([fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareLabel, fcSoftwareIcon], { opacity: 0, x: 20, duration: 0.8, ease: 'power3.out', stagger: 0.1, delay: 0.2 });
    }

    // Filtering logic
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            const wrapper = document.getElementById('showcaseList');
            
            // Reset to original HTML
            wrapper.innerHTML = allSlidesHTML;
            
            if (filter !== 'all') {
                const slides = wrapper.querySelectorAll('.showcase-slide');
                slides.forEach(slide => {
                    if (slide.getAttribute('data-category') !== filter) {
                        slide.remove();
                    }
                });
            }
            
            initSwiper();
            const firstFiltered = wrapper.querySelector('.showcase-slide');
            if (firstFiltered) {
                isAnimating = false;
                selectProject(firstFiltered);
            }
        });
    });
});
</script>
@endsection
