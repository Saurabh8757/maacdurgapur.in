@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/blog.css') }}">
@endsection

@section('content')
<!-- Blog Hero -->
<section class="blog-hero">
    <div class="blog-hero-bg"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="blog-hero-title">Creative Insights & News</h1>
        <p class="blog-hero-subtitle">Explore the latest trends in Animation, VFX, UI/UX Design, Gaming, and AI technology from the experts at MAAC Durgapur.</p>
        
        <div class="blog-categories">
            <button class="blog-category-btn active" data-filter="all">All Articles</button>
            <button class="blog-category-btn" data-filter="animation">Animation & VFX</button>
            <button class="blog-category-btn" data-filter="gaming">Gaming</button>
            <button class="blog-category-btn" data-filter="design">UI/UX & Design</button>
            <button class="blog-category-btn" data-filter="ai">AI & Tech</button>
        </div>
    </div>
</section>

<!-- Blog Grid Section -->
<section class="blog-section">
    <div class="blog-container">
        <div class="blog-grid" id="blogGrid">
            
            <!-- Blog Card 1 -->
            <article class="blog-card" data-category="animation">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">Animation & VFX</div>
                    <!-- Placeholder image -->
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800" alt="Future of VFX" class="blog-card-image" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Oct 12, 2025</span>
                        <span>5 min read</span>
                    </div>
                    <h3 class="blog-card-title">How AI is Revolutionizing the VFX Pipeline in 2026</h3>
                    <p class="blog-card-excerpt">Discover how major studios are integrating AI-powered compositing and rotoscoping to cut production time in half without sacrificing quality.</p>
                    <div class="blog-card-footer">
                        <a href="#" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>

            <!-- Blog Card 2 -->
            <article class="blog-card" data-category="design">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">UI/UX & Design</div>
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=800" alt="UI/UX Trends" class="blog-card-image" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Sep 28, 2025</span>
                        <span>4 min read</span>
                    </div>
                    <h3 class="blog-card-title">The Death of Flat Design: Rise of Neumorphism & 3D Web</h3>
                    <p class="blog-card-excerpt">Flat design has ruled the web for a decade, but immersive 3D elements and tactile UI components are rapidly becoming the new standard for premium brands.</p>
                    <div class="blog-card-footer">
                        <a href="#" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>

            <!-- Blog Card 3 -->
            <article class="blog-card" data-category="gaming">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">Gaming</div>
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800" alt="Unreal Engine 5" class="blog-card-image" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Sep 15, 2025</span>
                        <span>7 min read</span>
                    </div>
                    <h3 class="blog-card-title">Getting Started with Unreal Engine 5: Nanite & Lumen Explained</h3>
                    <p class="blog-card-excerpt">A beginner-friendly breakdown of how Unreal Engine 5's revolutionary rendering technologies are changing game development forever.</p>
                    <div class="blog-card-footer">
                        <a href="#" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>

            <!-- Blog Card 4 -->
            <article class="blog-card" data-category="ai">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">AI & Tech</div>
                    <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&q=80&w=800" alt="Generative AI" class="blog-card-image" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Aug 30, 2025</span>
                        <span>6 min read</span>
                    </div>
                    <h3 class="blog-card-title">Prompt Engineering for Artists: Mastering Midjourney & DALL-E</h3>
                    <p class="blog-card-excerpt">Learn how traditional artists are leveraging generative AI as an advanced brainstorming and concept art tool rather than seeing it as competition.</p>
                    <div class="blog-card-footer">
                        <a href="#" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>

            <!-- Blog Card 5 -->
            <article class="blog-card" data-category="animation">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">Animation & VFX</div>
                    <img src="https://images.unsplash.com/photo-1616469829941-c7200edec809?auto=format&fit=crop&q=80&w=800" alt="3D Modeling" class="blog-card-image" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Aug 12, 2025</span>
                        <span>8 min read</span>
                    </div>
                    <h3 class="blog-card-title">Blender vs. Maya: Which Should You Learn First?</h3>
                    <p class="blog-card-excerpt">An objective comparison of the two leading 3D software packages, breaking down industry standard expectations versus open-source flexibility.</p>
                    <div class="blog-card-footer">
                        <a href="#" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Blog Category Filtering
    const filterBtns = document.querySelectorAll('.blog-category-btn');
    const blogCards = document.querySelectorAll('.blog-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            // Filter cards
            blogCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || filter === category) {
                    card.style.display = 'flex';
                    // small animation effect
                    card.style.animation = 'none';
                    card.offsetHeight; /* trigger reflow */
                    card.style.animation = null;
                    card.style.opacity = '0';
                    setTimeout(() => { card.style.opacity = '1'; }, 50);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
