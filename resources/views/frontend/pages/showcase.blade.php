@extends('frontend.layout.app')

@section('meta_title', 'Student Showcase – Creative Work by MAAC Durgapur Students')
@section('meta_description', 'Explore the amazing portfolios and creative works in Animation, VFX, 3D Design, and UI/UX created by the talented students of MAAC Durgapur.')
@section('canonical_url', url()->current())
@section('og_title', 'Student Showcase – Creative Work by MAAC Durgapur Students')
@section('og_description', 'Explore the amazing portfolios and creative works in Animation, VFX, 3D Design, and UI/UX created by the talented students of MAAC Durgapur.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}?v={{ time() }}">
<style>
    .modal-info-grid {
        grid-template-columns: 1fr;
    }
    @media (min-width: 992px) {
        .modal-info-grid {
            grid-template-columns: 2fr 1fr;
        }
    }
    .showcase-modal-close:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        transform: scale(1.1);
    }
    /* Hide scrollbar for cleaner UI */
    .showcase-modal::-webkit-scrollbar {
        width: 8px;
    }
    .showcase-modal::-webkit-scrollbar-track {
        background: #0b0f19;
    }
    .showcase-modal::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 4px;
    }
    .showcase-modal::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }
    .modal-image-area {
        width: 100vw;
        height: 40vh; /* Smaller on mobile to reveal content below */
        background: #000;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .modal-slider-wrapper {
        width: 100%;
        height: 100%;
        padding: 10px; /* Small padding on mobile */
    }
    @media (min-width: 768px) {
        .modal-image-area {
            height: 85vh; /* Large cinematic feel on desktop */
        }
        .modal-slider-wrapper {
            padding: 40px; /* Large padding on desktop */
        }
    }
</style>
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
                        
                        <p class="fc-desc" id="fcDesc" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 5px;">Description...</p>
                        <a href="javascript:void(0)" id="fcSeeMoreBtn" style="color: #F59E0B; font-weight: bold; font-size: 0.9rem; text-decoration: none; display: inline-block; margin-bottom: 15px;">See More <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i></a>
                        
                        <div class="fc-software-wrap">
                            <span class="fc-software-label" id="fcSoftwareLabel" style="display:none;">Software Used</span>
                            <div id="fcSoftwareIconsContainer" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px;">
                                <!-- Icons appended dynamically -->
                            </div>
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
                                    $iconUrl2 = $project->softwareIcon2 ? asset(str_starts_with($project->softwareIcon2->storage_key, 'storage/') ? $project->softwareIcon2->storage_key : 'storage/' . $project->softwareIcon2->storage_key) : '';
                                    $iconUrl3 = $project->softwareIcon3 ? asset(str_starts_with($project->softwareIcon3->storage_key, 'storage/') ? $project->softwareIcon3->storage_key : 'storage/' . $project->softwareIcon3->storage_key) : '';
                                    $iconUrl4 = $project->softwareIcon4 ? asset(str_starts_with($project->softwareIcon4->storage_key, 'storage/') ? $project->softwareIcon4->storage_key : 'storage/' . $project->softwareIcon4->storage_key) : '';
                                    $iconUrl5 = $project->softwareIcon5 ? asset(str_starts_with($project->softwareIcon5->storage_key, 'storage/') ? $project->softwareIcon5->storage_key : 'storage/' . $project->softwareIcon5->storage_key) : '';
                                    
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
                                     data-icon2="{{ $iconUrl2 }}"
                                     data-icon3="{{ $iconUrl3 }}"
                                     data-icon4="{{ $iconUrl4 }}"
                                     data-icon5="{{ $iconUrl5 }}"
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

<!-- Detail Modal -->
<div id="showcaseDetailModal" class="showcase-modal" style="display: none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100vw; height: 100vh; overflow-y: auto; overflow-x: hidden; background-color: #050505;">
    <!-- Fixed Close Button -->
    <button class="showcase-modal-close" style="position: fixed; top: 25px; right: 30px; width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: #fff; font-size: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 100000; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">&times;</button>
    
    <!-- Full Screen Image Area -->
    <div class="modal-image-area">
        <div id="modalSliderWrapper" class="modal-slider-wrapper">
            <!-- Img with object-fit: contain will be injected here -->
        </div>
    </div>

    <!-- Premium Details Area -->
    <div style="width: 100%; background: #050505; position: relative;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 50px 20px 80px;">
            <div class="modal-info-grid">
                <div class="modal-info-main">
                    <h2 id="modalTitle" style="font-size: 2.8rem; font-weight: 800; margin-bottom: 10px; line-height: 1.2; background: linear-gradient(90deg, #fff, #cbd5e1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Project Title</h2>
                    <h5 id="modalStudent" style="color: #F59E0B; font-size: 1.2rem; font-weight: 600; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 2px;">Crafted by "Student Name"</h5>
                    <div id="modalDesc" style="color: #94a3b8; font-size: 1.15rem; line-height: 1.8; text-align: justify; white-space: pre-wrap;">Full description here...</div>
                </div>
                
                <div class="modal-info-sidebar" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 30px; border-radius: 16px; height: fit-content;">
                    <h4 style="font-size: 1.2rem; color: #fff; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">Software Used</h4>
                    <div id="modalSoftwareIconsContainer" style="display: flex; flex-wrap: wrap; gap: 15px;">
                        <!-- Icons appended dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
    const fcSoftwareIconsContainer = document.getElementById('fcSoftwareIconsContainer');
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
                slideChange: function () {
                    const activeSlide = this.slides[this.activeIndex];
                    if(activeSlide) selectProject(activeSlide);
                },
                click: function (swiper, event) {
                    if (swiper.clickedSlide) {
                        swiper.slideTo(swiper.clickedIndex);
                        selectProject(swiper.clickedSlide);
                    }
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
        const thumbUrl = item.getAttribute('data-thumb');
        
        const icons = [
            item.getAttribute('data-icon'),
            item.getAttribute('data-icon2'),
            item.getAttribute('data-icon3'),
            item.getAttribute('data-icon4'),
            item.getAttribute('data-icon5')
        ].filter(i => i && i.trim() !== '');
        
        const tl = gsap.timeline({
            onComplete: () => { isAnimating = false; }
        });
        
        tl.to([fcImage, fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareIconsContainer, fcSoftwareLabel], { 
            opacity: 0, y: 10, duration: 0.3, ease: 'power2.in', stagger: 0.02
        })
        .call(() => {
            fcTitle.textContent = title;
            fcStudent.textContent = `"${student}"`;
            fcCategory.textContent = categoryName;
            fcDesc.textContent = desc;
            fcImage.src = thumbUrl;
            
            fcSoftwareIconsContainer.innerHTML = '';
            if (icons.length > 0) {
                icons.forEach(url => {
                    const img = document.createElement('img');
                    img.src = url;
                    img.style.width = '32px';
                    img.style.height = '32px';
                    img.style.objectFit = 'contain';
                    fcSoftwareIconsContainer.appendChild(img);
                });
                fcSoftwareLabel.style.display = 'block';
            } else {
                fcSoftwareLabel.style.display = 'none';
            }
        })
        .to(fcImage, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' })
        .to([fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareLabel, fcSoftwareIconsContainer], { 
            opacity: 1, y: 0, duration: 0.5, ease: 'power3.out', stagger: 0.05
        }, "-=0.4");
    }

    // Initialize
    initSwiper();
    
    const initialSlide = (swiper && swiper.slides && swiper.slides.length > 0) ? swiper.slides[swiper.activeIndex] : document.querySelector('.showcase-slide');
    
    if (initialSlide) {
        fcTitle.textContent = initialSlide.getAttribute('data-title');
        fcStudent.textContent = `"${initialSlide.getAttribute('data-student')}"`;
        fcCategory.textContent = initialSlide.getAttribute('data-category-name');
        fcDesc.textContent = initialSlide.getAttribute('data-desc');
        fcImage.src = initialSlide.getAttribute('data-thumb');
        
        const icons = [
            initialSlide.getAttribute('data-icon'),
            initialSlide.getAttribute('data-icon2'),
            initialSlide.getAttribute('data-icon3'),
            initialSlide.getAttribute('data-icon4'),
            initialSlide.getAttribute('data-icon5')
        ].filter(i => i && i.trim() !== '');

        fcSoftwareIconsContainer.innerHTML = '';
        if (icons.length > 0) {
            icons.forEach(url => {
                const img = document.createElement('img');
                img.src = url;
                img.style.width = '32px';
                img.style.height = '32px';
                img.style.objectFit = 'contain';
                fcSoftwareIconsContainer.appendChild(img);
            });
            fcSoftwareLabel.style.display = 'block';
        } else {
            fcSoftwareLabel.style.display = 'none';
        }
        
        gsap.from(fcImage, { opacity: 0, scale: 0.95, duration: 1, ease: 'power3.out' });
        gsap.from([fcTitle, fcCategory, fcStudent, fcDesc, fcSoftwareLabel, fcSoftwareIconsContainer], { opacity: 0, x: 20, duration: 0.8, ease: 'power3.out', stagger: 0.1, delay: 0.2 });
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

    // Modal Logic
    const seeMoreBtn = document.getElementById('fcSeeMoreBtn');
    const modal = document.getElementById('showcaseDetailModal');
    const modalClose = document.querySelector('.showcase-modal-close');
    const modalTitle = document.getElementById('modalTitle');
    const modalStudent = document.getElementById('modalStudent');
    const modalDesc = document.getElementById('modalDesc');
    const modalSliderWrapper = document.getElementById('modalSliderWrapper');

    seeMoreBtn.addEventListener('click', () => {
        const activeSlide = document.querySelector('.showcase-slide.swiper-slide-active') || document.querySelector('.showcase-slide');
        if (!activeSlide) return;

        modalTitle.textContent = activeSlide.getAttribute('data-title');
        modalStudent.textContent = `Crafted by "${activeSlide.getAttribute('data-student')}"`;
        modalDesc.textContent = activeSlide.getAttribute('data-desc');

        // Populate modal slider wrapper with the single thumbnail
        modalSliderWrapper.innerHTML = '';
        const thumbUrl = activeSlide.getAttribute('data-thumb');

        if (thumbUrl && thumbUrl.trim() !== '') {
            modalSliderWrapper.innerHTML = `<img src="${thumbUrl}" style="width: 100%; height: 100%; object-fit: contain;">`;
        }

        // Populate Software Icons
        const modalSoftwareIconsContainer = document.getElementById('modalSoftwareIconsContainer');
        modalSoftwareIconsContainer.innerHTML = '';
        const icons = [
            activeSlide.getAttribute('data-icon'),
            activeSlide.getAttribute('data-icon2'),
            activeSlide.getAttribute('data-icon3'),
            activeSlide.getAttribute('data-icon4'),
            activeSlide.getAttribute('data-icon5')
        ].filter(i => i && i.trim() !== '');

        if (icons.length > 0) {
            icons.forEach(url => {
                const img = document.createElement('img');
                img.src = url;
                img.style.width = '48px';
                img.style.height = '48px';
                img.style.objectFit = 'contain';
                img.style.background = 'rgba(255,255,255,0.05)';
                img.style.padding = '8px';
                img.style.borderRadius = '12px';
                img.style.border = '1px solid rgba(255,255,255,0.1)';
                modalSoftwareIconsContainer.appendChild(img);
            });
            modalSoftwareIconsContainer.parentElement.style.display = 'block';
        } else {
            modalSoftwareIconsContainer.parentElement.style.display = 'none';
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Hide navigation if they exist since there's no swiper anymore
        const prevBtn = document.querySelector('.modal-prev');
        const nextBtn = document.querySelector('.modal-next');
        const pagination = document.querySelector('.modal-pagination');
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (pagination) pagination.style.display = 'none';
    });

    modalClose.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    window.addEventListener('click', (e) => {
        if (e.target == modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>
@endsection
