@extends('frontend.layout.app')

@section('meta_title', 'Blog – MAAC Durgapur | Animation, VFX, Gaming & Creative Industry Insights')
@section('meta_description', 'Read the latest insights, tips, and trends on Animation, VFX, Graphic Design, and AI from MAAC Durgapur experts.')
@section('canonical_url', url()->current())
@section('og_title', 'Blog – MAAC Durgapur | Animation, VFX, Gaming & Creative Industry Insights')
@section('og_description', 'Read the latest insights, tips, and trends on Animation, VFX, Graphic Design, and AI from MAAC Durgapur experts.')


@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/blog.css') }}?v={{ time() }}">
@endsection

@section('content')
<!-- Blog Hero -->
<section class="blog-hero">
    <div class="blog-hero-bg">
        <div class="hero-gradient-mesh"></div>
        <div class="hero-glass-overlay"></div>
        <div class="hero-bottom-fade"></div>
    </div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 class="blog-hero-title">Creative Insights & News</h1>
        <p class="blog-hero-subtitle">Explore the latest trends in Animation, VFX, UI/UX Design, Gaming, and AI technology from the experts at MAAC Durgapur.</p>
        
        <div class="blog-categories">
            <button class="blog-category-btn active" data-filter="all">All Articles</button>
            @foreach($categories as $category)
                <button class="blog-category-btn" data-filter="{{ strtolower(str_replace(' ', '-', $category->name)) }}">{{ $category->name }}</button>
            @endforeach
        </div>
    </div>
</section>

<!-- Blog Grid Section -->
<section class="blog-section">
    <div class="blog-container">
        <div class="blog-grid" id="blogGrid">
            
            @forelse($blogs as $blog)
            <!-- Blog Card -->
            <article class="blog-card" data-category="{{ $blog->category ? strtolower(str_replace(' ', '-', $blog->category->name)) : 'uncategorized' }}">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">{{ $blog->category ? $blog->category->name : 'Uncategorized' }}</div>
                    @if($blog->featuredImage)
                        <img src="{{ asset('storage/' . $blog->featuredImage->storage_key) }}" alt="{{ $blog->title }}" class="blog-card-image" loading="lazy">
                    @else
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800" alt="{{ $blog->title }}" class="blog-card-image" loading="lazy">
                    @endif
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                        @if($blog->reading_time)
                            <span>{{ $blog->reading_time }}</span>
                        @endif
                    </div>
                    <h3 class="blog-card-title">{{ $blog->title }}</h3>
                    <p class="blog-card-excerpt">{{ $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content), 120) }}</p>
                    <div class="blog-card-footer">
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-card-readmore">Read Full Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-12 text-center py-5">
                <h3>No blogs available at the moment.</h3>
                <p>Please check back later.</p>
            </div>
            @endforelse

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
