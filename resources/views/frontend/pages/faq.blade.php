@extends('frontend.layout.app')

@section('meta_title', 'FAQ – Frequently Asked Questions | MAAC Durgapur')
@section('meta_description', 'Find answers to common questions about MAAC Durgapur courses, admissions, fees, placements, and more.')
@section('canonical_url', url()->current())
@section('og_title', 'FAQ – Frequently Asked Questions | MAAC Durgapur')
@section('og_description', 'Find answers to common questions about MAAC Durgapur courses, admissions, fees, placements, and more.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/faq.css') }}?v={{ time() }}">
@endsection

@section('content')
<!-- FAQ Hero -->
<section class="faq-hero">
    <div class="faq-hero-bg">
        <!-- Clean, performant animated gradient background -->
        <div class="hero-gradient-mesh"></div>
        <div class="hero-glass-overlay"></div>
        <div class="hero-bottom-fade"></div>
    </div>
    <div class="container" style="position: relative; z-index: 10;">
        <h1 class="faq-hero-title">Frequently Asked Questions</h1>
        <p class="faq-hero-subtitle">Everything you need to know about admissions, courses, fees, and career opportunities at MAAC Durgapur.</p>
        
        <div class="faq-categories">
            <button class="faq-category-btn active" data-filter="all">All Questions</button>
            @foreach($categories as $category)
                <button class="faq-category-btn" data-filter="cat-{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Accordion Section -->
<section class="faq-section" style="position: relative; overflow: hidden;">
    <!-- 3D Bouncing Ball & Floor Background -->
    <div class="faq-section-bg">
        <div class="faq-floor"></div>
        <div class="faq-ball-wrapper">
            <div class="faq-ball-shadow"></div>
            <div class="faq-ball"></div>
        </div>
    </div>
    <div class="faq-container" style="position: relative; z-index: 10;">
        
        <!-- Dynamic FAQs -->
        @foreach($categories as $category)
            @foreach($category->faqs as $faq)
            <div class="faq-item" data-category="cat-{{ $category->id }}">
                <div class="faq-question">
                    <span>{{ $faq->question }}</span>
                    <div class="faq-toggle-icon"></div>
                </div>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach

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
<style>
    /* Prevent horizontal scroll issues from 3D */
    body { overflow-x: hidden; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Staggered Entrance Animation for FAQ Items
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        item.style.transitionDelay = `${index * 0.1}s`;
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
            // Remove inline transition after entrance so CSS hover transition works
            setTimeout(() => { 
                item.style.transition = ''; 
                item.style.transitionDelay = ''; 
            }, 600);
        }, 100);
    });

    // Accordion Logic
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
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(10px)';
                    setTimeout(() => { 
                        item.style.transition = 'all 0.4s ease';
                        item.style.opacity = '1'; 
                        item.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

});
</script>
@endsection
