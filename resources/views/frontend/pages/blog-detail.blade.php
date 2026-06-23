@extends('frontend.layout.app')

@section('meta_title', $blog->meta_title ?: $blog->title)
@section('meta_description', $blog->meta_description ?: $blog->excerpt)
@section('canonical_url', $blog->canonical_url ?: url()->current())
@section('og_title', $blog->og_title ?: ($blog->meta_title ?: $blog->title))
@section('og_description', $blog->og_description ?: ($blog->meta_description ?: $blog->excerpt))
@if($blog->featuredImage)
@section('og_image', asset('storage/' . $blog->featuredImage->storage_key))
@endif

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/blog.css') }}?v={{ time() }}">
<style>
/* ============================================================
   BLOG DETAIL — PREMIUM RESPONSIVE STYLES
   Uses the same design tokens as the main site
   ============================================================ */

/* ---------- HERO ---------- */
.bd-hero {
    position: relative;
    padding: 160px 20px 80px;
    overflow: hidden;
    background: radial-gradient(ellipse at 50% 0%, #0f1535 0%, #020617 60%);
}

.bd-hero::before {
    content: '';
    position: absolute;
    top: -30%; left: 50%;
    transform: translateX(-50%);
    width: 800px; height: 800px;
    background: radial-gradient(circle, rgba(138,43,226,0.12) 0%, rgba(255,106,0,0.06) 40%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

.bd-hero::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 120px;
    background: linear-gradient(to top, #020617, transparent);
    z-index: 1;
    pointer-events: none;
}

/* ---------- HEADER ---------- */
.bd-header {
    position: relative;
    z-index: 2;
    max-width: 860px;
    margin: 0 auto;
    text-align: center;
}

.bd-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 0.9rem;
    text-decoration: none;
    margin-bottom: 28px;
    transition: color 0.3s;
}
.bd-back:hover { color: var(--orange); }

.bd-cat {
    display: inline-block;
    padding: 6px 18px;
    background: linear-gradient(135deg, rgba(138,43,226,0.25), rgba(255,106,0,0.18));
    color: var(--orange);
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 22px;
    border: 1px solid rgba(255,106,0,0.2);
}

.bd-title {
    font-family: 'Rajdhani', sans-serif;
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 28px;
    letter-spacing: -0.5px;
}

.bd-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    margin-bottom: 0;
}

.bd-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--text-muted);
    font-size: 0.9rem;
}
.bd-meta-item svg { flex-shrink: 0; stroke: var(--orange); opacity: 0.8; }

/* ---------- FEATURED IMAGE ---------- */
.bd-img-wrap {
    position: relative;
    z-index: 2;
    max-width: 960px;
    margin: -20px auto 0;
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid rgba(138,43,226,0.3);
    box-shadow:
        0 30px 60px rgba(0,0,0,0.5),
        0 0 60px rgba(138,43,226,0.08);
    aspect-ratio: 16 / 9;
}
.bd-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}

/* ---------- ARTICLE BODY ---------- */
.bd-body {
    padding: 70px 20px 90px;
    background-image: url('../images/showcase-bg.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
}
.bd-body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(8,10,25,0.8);
    z-index: 0;
}

.bd-article {
    position: relative;
    z-index: 1;
    max-width: 780px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.85;
    color: var(--text-muted);
}

/* Typography inside article */
.bd-article h2 {
    font-family: 'Rajdhani', sans-serif;
    font-size: 1.85rem;
    font-weight: 700;
    color: #fff;
    margin: 52px 0 18px;
    padding-bottom: 12px;
    border-bottom: 2px solid rgba(255,106,0,0.25);
    letter-spacing: 0.5px;
}
.bd-article h3 {
    font-family: 'Rajdhani', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-light);
    margin: 40px 0 14px;
}
.bd-article p {
    margin-bottom: 22px;
    color: var(--text-muted);
}
.bd-article a {
    color: var(--orange);
    text-decoration: underline;
    text-underline-offset: 3px;
}
.bd-article a:hover { color: var(--orange-light); }

.bd-article strong { color: var(--text-light); }

.bd-article img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-md);
    margin: 30px 0;
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
    border: 1px solid rgba(138,43,226,0.2);
}

.bd-article blockquote {
    position: relative;
    margin: 42px 0;
    padding: 28px 28px 28px 32px;
    border-left: 4px solid var(--orange);
    background: rgba(255,106,0,0.04);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    font-size: 1.15rem;
    font-style: italic;
    color: var(--text-light);
    backdrop-filter: blur(4px);
}
.bd-article blockquote::before {
    content: '\201C';
    position: absolute;
    top: -8px; left: 14px;
    font-size: 4rem;
    color: var(--orange);
    opacity: 0.2;
    line-height: 1;
    font-style: normal;
}

.bd-article ul, .bd-article ol {
    margin: 0 0 24px 0;
    padding-left: 24px;
}
.bd-article li {
    margin-bottom: 12px;
    color: var(--text-muted);
}
.bd-article li::marker {
    color: var(--orange);
}

.bd-article figure {
    margin: 32px 0;
}
.bd-article figure img { margin: 0; }
.bd-article figcaption {
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 10px;
    font-style: italic;
}

/* ---------- TAGS ---------- */
.bd-tags {
    margin-top: 56px;
    padding-top: 32px;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.bd-tags-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    color: var(--text-light);
    font-size: 0.9rem;
    margin-right: 4px;
}
.bd-tags-label svg { stroke: var(--orange); }

.bd-tag {
    display: inline-block;
    padding: 6px 16px;
    background: rgba(255,255,255,0.04);
    color: var(--text-muted);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: var(--radius-full);
    font-size: 0.82rem;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    cursor: default;
}
.bd-tag:hover {
    background: var(--orange);
    border-color: var(--orange);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,106,0,0.25);
}

/* ---------- SHARE BAR ---------- */
.bd-share {
    margin-top: 40px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.bd-share-label {
    font-weight: 600;
    color: var(--text-light);
    font-size: 0.9rem;
}
.bd-share-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    color: var(--text-muted);
    text-decoration: none;
    transition: all 0.3s ease;
}
.bd-share-btn:hover {
    background: var(--orange);
    border-color: var(--orange);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(255,106,0,0.3);
}

/* ---------- RELATED POSTS ---------- */
.bd-related {
    padding: 90px 20px;
    background: radial-gradient(ellipse at 50% 100%, #0f1535 0%, #020617 60%);
    position: relative;
}
.bd-related::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(138,43,226,0.3), transparent);
}

.bd-related-head {
    text-align: center;
    margin-bottom: 50px;
}
.bd-related-head h2 {
    font-family: 'Rajdhani', sans-serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.bd-related-head p {
    color: var(--text-muted);
    font-size: 1.05rem;
    margin-top: 8px;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 1024px) {
    .bd-img-wrap { max-width: 90%; margin-top: -10px; }
}

@media (max-width: 768px) {
    .bd-hero { padding: 130px 16px 60px; }
    .bd-title { font-size: 1.75rem; letter-spacing: 0; }
    .bd-meta { gap: 14px; }
    .bd-meta-item { font-size: 0.82rem; }
    .bd-img-wrap {
        max-width: 100%;
        border-radius: var(--radius-md);
        margin-top: 0;
    }
    .bd-body { padding: 40px 16px 60px; }
    .bd-article { font-size: 1rem; line-height: 1.75; }
    .bd-article h2 { font-size: 1.5rem; margin-top: 36px; }
    .bd-article h3 { font-size: 1.2rem; margin-top: 28px; }
    .bd-article blockquote { padding: 20px 18px 20px 22px; font-size: 1rem; }
    .bd-tags { gap: 8px; }
    .bd-related { padding: 60px 16px; }
    .bd-related-head h2 { font-size: 1.7rem; }
    .blog-grid { grid-template-columns: 1fr !important; }
}

@media (max-width: 480px) {
    .bd-hero { padding: 120px 14px 50px; }
    .bd-title { font-size: 1.45rem; }
    .bd-cat { font-size: 0.72rem; padding: 5px 14px; }
    .bd-meta { flex-direction: column; gap: 8px; }
    .bd-article { font-size: 0.95rem; }
    .bd-share { flex-wrap: wrap; }
}
</style>
@endsection

@section('content')
<!-- ====== BLOG DETAIL HERO ====== -->
<section class="bd-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="bd-header">
            <a href="{{ route('blogs.index') }}" class="bd-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Blog
            </a>

            @if($blog->category)
            <div class="bd-cat">{{ $blog->category->name }}</div>
            @endif

            <h1 class="bd-title">{{ $blog->title }}</h1>

            <div class="bd-meta">
                <span class="bd-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    {{ $blog->author ?? 'Admin' }}
                </span>
                <span class="bd-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                </span>
                @if($blog->reading_time)
                <span class="bd-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    {{ $blog->reading_time }}
                </span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ====== FEATURED IMAGE ====== -->
<div class="bd-img-wrap">
    @if($blog->featuredImage)
        <img src="{{ asset('storage/' . $blog->featuredImage->storage_key) }}" alt="{{ $blog->title }}">
    @else
        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1200" alt="{{ $blog->title }}">
    @endif
</div>

<!-- ====== ARTICLE BODY ====== -->
<section class="bd-body">
    <article class="bd-article">
        {!! $blog->content !!}

        {{-- Tags --}}
        @if(!empty($blog->tags) && is_array($blog->tags))
        <div class="bd-tags">
            <span class="bd-tags-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                Tags:
            </span>
            @foreach($blog->tags as $tag)
                <span class="bd-tag">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Share --}}
        <div class="bd-share">
            <span class="bd-share-label">Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="bd-share-btn" aria-label="Share on Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="bd-share-btn" aria-label="Share on X">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="bd-share-btn" aria-label="Share on WhatsApp">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}" target="_blank" rel="noopener" class="bd-share-btn" aria-label="Share on LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
        </div>
    </article>
</section>

<!-- ====== RELATED POSTS ====== -->
@if($relatedPosts->isNotEmpty())
<section class="bd-related">
    <div class="container">
        <div class="bd-related-head">
            <h2>Related Articles</h2>
            <p>Continue reading from the same category</p>
        </div>
        <div class="blog-grid" style="max-width: 1200px; margin: 0 auto;">
            @foreach($relatedPosts as $related)
            <article class="blog-card">
                <div class="blog-card-image-wrap">
                    <div class="blog-card-category">{{ $related->category ? $related->category->name : 'Uncategorized' }}</div>
                    @if($related->featuredImage)
                        <img src="{{ asset('storage/' . $related->featuredImage->storage_key) }}" alt="{{ $related->title }}" class="blog-card-image" loading="lazy">
                    @else
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800" alt="{{ $related->title }}" class="blog-card-image" loading="lazy">
                    @endif
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: text-top; margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ $related->published_at ? $related->published_at->format('M d, Y') : $related->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="blog-card-title">{{ $related->title }}</h3>
                    <p class="blog-card-excerpt">{{ $related->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($related->content), 100) }}</p>
                    <div class="blog-card-footer">
                        <a href="{{ route('blogs.show', $related->slug) }}" class="blog-card-readmore">Read Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
