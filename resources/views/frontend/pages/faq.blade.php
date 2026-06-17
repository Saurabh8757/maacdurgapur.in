@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/faq.css') }}">
@endsection

@section('content')
<!-- FAQ Hero -->
<section class="faq-hero">
    <div class="faq-hero-bg"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="faq-hero-title">Frequently Asked Questions</h1>
        <p class="faq-hero-subtitle">Everything you need to know about admissions, courses, fees, and career opportunities at MAAC Durgapur.</p>
        
        <div class="faq-categories">
            <button class="faq-category-btn active" data-filter="all">All Questions</button>
            <button class="faq-category-btn" data-filter="admissions">Admissions & Fees</button>
            <button class="faq-category-btn" data-filter="courses">Courses & Software</button>
            <button class="faq-category-btn" data-filter="placement">Placements</button>
        </div>
    </div>
</section>

<!-- FAQ Accordion Section -->
<section class="faq-section">
    <div class="faq-container">
        
        <!-- Category: Admissions -->
        <div class="faq-item" data-category="admissions">
            <div class="faq-question">
                <span>What is the admission procedure at MAAC Durgapur?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    The admission process is simple. You can book a free career counselling session with our experts. Once you select the course that fits your goals, our team will guide you through the enrollment and documentation process.
                </div>
            </div>
        </div>

        <div class="faq-item" data-category="admissions">
            <div class="faq-question">
                <span>Do you offer EMI or installment facilities for course fees?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Yes, we understand that quality education should be accessible. MAAC Durgapur offers flexible EMI and installment options to help students manage their course fees conveniently.
                </div>
            </div>
        </div>

        <!-- Category: Courses -->
        <div class="faq-item" data-category="courses">
            <div class="faq-question">
                <span>Do I need a background in art to join a 3D Animation or VFX course?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Not at all! Our courses are designed to take you from the very basics to advanced professional levels. While a background in fine arts is helpful, it is completely optional.
                </div>
            </div>
        </div>

        <div class="faq-item" data-category="courses">
            <div class="faq-question">
                <span>Which software will I learn during the VFX program?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Our VFX courses cover industry-standard software including Nuke, Houdini FX, Autodesk Maya, Adobe After Effects, and Premiere Pro. We also introduce AI-powered tools to ensure you are future-ready.
                </div>
            </div>
        </div>

        <!-- Category: Placements -->
        <div class="faq-item" data-category="placement">
            <div class="faq-question">
                <span>Does MAAC provide placement assistance?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Absolutely. MAAC has a dedicated Placement Cell that provides 100% placement assistance. We conduct regular portfolio reviews, mock interviews, and campus recruitment drives with top studios across India.
                </div>
            </div>
        </div>

        <div class="faq-item" data-category="placement">
            <div class="faq-question">
                <span>What kind of jobs can I get after completing a MAAC course?</span>
                <div class="faq-toggle-icon"></div>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-inner">
                    Our alumni work as 3D Animators, VFX Artists, Motion Graphic Designers, UI/UX Designers, Video Editors, and Game Assets Creators in top media houses, advertising agencies, and gaming studios.
                </div>
            </div>
        </div>

        <!-- Bottom CTA to fix dead-end -->
        <div class="faq-contact-cta">
            <h3>Still have questions?</h3>
            <p>Our career counselors are ready to help you map out your future.</p>
            <a href="#" class="faq-btn open-modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                Book Free Counselling
            </a>
        </div>

    </div>
</section>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion Logic
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            
            // Close all others
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('open');
                const otherAnswer = otherItem.querySelector('.faq-answer');
                if(otherAnswer) otherAnswer.style.maxHeight = null;
            });

            // Toggle current
            if (!isOpen) {
                item.classList.add('open');
                const answer = item.querySelector('.faq-answer');
                if(answer) answer.style.maxHeight = answer.scrollHeight + "px";
            }
        });
    });

    // Category Filtering
    const filterBtns = document.querySelectorAll('.faq-category-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            // Filter items
            faqItems.forEach(item => {
                // Close open items during filter
                item.classList.remove('open');
                const answer = item.querySelector('.faq-answer');
                if(answer) answer.style.maxHeight = null;

                const category = item.getAttribute('data-category');
                
                if (filter === 'all' || filter === category) {
                    item.style.display = 'block';
                    // small animation effect
                    item.style.opacity = '0';
                    setTimeout(() => { item.style.opacity = '1'; }, 50);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
