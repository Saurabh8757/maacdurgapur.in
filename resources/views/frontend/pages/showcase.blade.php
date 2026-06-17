@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}">
@endsection

@section('content')
<!-- Showcase Hero -->
<section class="showcase-hero">
    <div class="showcase-hero-bg"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="showcase-hero-title">Student Showcase</h1>
        <p class="showcase-hero-subtitle">Witness the incredible portfolios, showreels, and award-winning projects created by our students across VFX, Gaming, and UI/UX.</p>
        
        <div class="showcase-filters">
            <button class="showcase-filter-btn active" data-filter="all">All Projects</button>
            <button class="showcase-filter-btn" data-filter="3d">3D & VFX</button>
            <button class="showcase-filter-btn" data-filter="gaming">Gaming Environments</button>
            <button class="showcase-filter-btn" data-filter="uiux">UI/UX Design</button>
        </div>
    </div>
</section>

<!-- Showcase Grid Section -->
<section class="showcase-section">
    <div class="showcase-container">
        <div class="showcase-grid" id="showcaseGrid">
            
            <!-- Showcase Item 1 -->
            <div class="showcase-card" data-category="3d">
                <div class="showcase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80&w=800" alt="Sci-Fi Corridor" class="showcase-card-image" loading="lazy">
                    <div class="showcase-play-btn">
                        <div class="showcase-play-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#0073e6" stroke="none"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                        </div>
                    </div>
                </div>
                <div class="showcase-card-content">
                    <div class="showcase-student-info">
                        <h3 class="showcase-student-name">Rahul Sharma</h3>
                        <span class="showcase-category-badge">3D & VFX</span>
                    </div>
                    <p class="showcase-description">A fully rendered sci-fi corridor utilizing Houdini FX for procedural generation and Maya for detailed assets. Lighting baked in Nuke.</p>
                    
                </div>
            </div>

            <!-- Showcase Item 2 -->
            <div class="showcase-card" data-category="uiux">
                <div class="showcase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=800" alt="Fintech Dashboard" class="showcase-card-image" loading="lazy">
                    <div class="showcase-play-btn">
                        <div class="showcase-play-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#0073e6" stroke="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                        </div>
                    </div>
                </div>
                <div class="showcase-card-content">
                    <div class="showcase-student-info">
                        <h3 class="showcase-student-name">Sneha Gupta</h3>
                        <span class="showcase-category-badge">UI/UX</span>
                    </div>
                    <p class="showcase-description">Comprehensive UX research and UI design for a conceptual Fintech application. Prototyped entirely in Figma with micro-animations.</p>
                    
                </div>
            </div>

            <!-- Showcase Item 3 -->
            <div class="showcase-card" data-category="gaming">
                <div class="showcase-card-image-wrap">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800" alt="Unreal Level Design" class="showcase-card-image" loading="lazy">
                    <div class="showcase-play-btn">
                        <div class="showcase-play-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#0073e6" stroke="none"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                        </div>
                    </div>
                </div>
                <div class="showcase-card-content">
                    <div class="showcase-student-info">
                        <h3 class="showcase-student-name">Aritra Das</h3>
                        <span class="showcase-category-badge">Gaming</span>
                    </div>
                    <p class="showcase-description">Real-time level design built in Unreal Engine 5 utilizing Nanite and Lumen. Custom textures painted via Substance Painter.</p>
                    
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Showcase Category Filtering
    const filterBtns = document.querySelectorAll('.showcase-filter-btn');
    const showcaseCards = document.querySelectorAll('.showcase-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            // Filter cards
            showcaseCards.forEach(card => {
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
