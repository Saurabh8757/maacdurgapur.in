@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}">
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

<!-- Showcase Split Section -->
<section class="showcase-split-section">
    <div class="showcase-split-container">
        <!-- Left Panel: Project Navigator -->
        <div class="showcase-left-pane">
            <div class="showcase-list" id="showcaseList">
                @forelse($showcaseProjects as $index => $project)
                    @php
                        $thumbUrl = $project->thumbnail ? asset($project->thumbnail->storage_key) : '';
                        $initials = collect(explode(' ', $project->student_name))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('');
                        $formattedIndex = sprintf('%02d', $index + 1);
                    @endphp
                    <div class="showcase-list-item" 
                         data-index="{{ $formattedIndex }}"
                         data-category="{{ $project->category->slug }}"
                         data-title="{{ $project->title }}"
                         data-student="{{ $project->student_name }}"
                         data-category-name="{{ $project->category->name }}"
                         data-desc="{{ $project->short_description }}"
                         data-video="{{ $project->video_url }}"
                         data-thumb="{{ $thumbUrl }}">
                        
                        <div class="sl-avatar">{{ strtoupper($initials) }}</div>
                        <span class="sl-number">{{ $formattedIndex }}</span>
                        <div class="sl-info">
                            <span class="sl-student">{{ $project->student_name }}</span>
                            <span class="sl-title">{{ Str::limit($project->title, 40) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="premium-empty-state">
                        <p>No projects available right now.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Panel: Project Preview -->
        <div class="showcase-right-pane" id="showcasePreviewPane">
            <div class="sr-large-number" id="srLargeNumber">01</div>
            <div class="sr-hero-bg" id="srHeroBg">
                <div class="sr-hero-overlay"></div>
            </div>
            
            <div class="sr-content">
                <div class="sr-glass-panel">
                    <div class="sr-header">
                        <span class="sr-category" id="srCategory">Category</span>
                        <h2 class="sr-title" id="srTitle">Project Title</h2>
                        <h3 class="sr-student" id="srStudent">Student Name</h3>
                    </div>
                    
                    <div class="sr-body">
                        <p class="sr-desc" id="srDesc">Project description goes here...</p>
                        
                        <div class="sr-tags" id="srTags">
                            <!-- Tags will be injected here dynamically -->
                        </div>
                        
                        <div class="sr-actions">
                            <a href="#" target="_blank" rel="noopener noreferrer" class="sr-btn" id="srVideoBtn">
                                View Project 
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                            <span class="sr-btn disabled" id="srNoVideoBtn" style="display:none;">Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listItems = document.querySelectorAll('.showcase-list-item');
    const filterBtns = document.querySelectorAll('.showcase-filter-btn');
    
    // DOM Elements for Preview
    const srHeroBg = document.getElementById('srHeroBg');
    const srLargeNumber = document.getElementById('srLargeNumber');
    const srCategory = document.getElementById('srCategory');
    const srTitle = document.getElementById('srTitle');
    const srStudent = document.getElementById('srStudent');
    const srDesc = document.getElementById('srDesc');
    const srTags = document.getElementById('srTags');
    const srVideoBtn = document.getElementById('srVideoBtn');
    const srNoVideoBtn = document.getElementById('srNoVideoBtn');
    const srGlassPanel = document.querySelector('.sr-glass-panel');
    
    let isAnimating = false;

    function selectProject(item) {
        if (isAnimating || item.classList.contains('active')) return;
        isAnimating = true;

        // Update active class
        listItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        
        // Scroll left list to active item smoothly
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Extract Data
        const indexStr = item.getAttribute('data-index');
        const title = item.getAttribute('data-title');
        const student = item.getAttribute('data-student');
        const categoryName = item.getAttribute('data-category-name');
        const desc = item.getAttribute('data-desc');
        const videoUrl = item.getAttribute('data-video');
        const thumbUrl = item.getAttribute('data-thumb');
        
        // GSAP Animation Timeline
        const tl = gsap.timeline({
            onComplete: () => { isAnimating = false; }
        });
        
        tl.to(srGlassPanel, { opacity: 0, y: 30, duration: 0.3, ease: 'power2.in' })
          .to(srLargeNumber, { opacity: 0, x: 20, duration: 0.3, ease: 'power2.in' }, "<")
          .to(srHeroBg, { scale: 1.1, opacity: 0, duration: 0.4, ease: 'power2.inOut' }, "<")
          .call(() => {
              // Update Content
              srLargeNumber.textContent = indexStr;
              srTitle.textContent = title;
              srStudent.textContent = student;
              srCategory.textContent = categoryName;
              srDesc.textContent = desc;
              
              if (thumbUrl) {
                  srHeroBg.style.backgroundImage = `url('${thumbUrl}')`;
              } else {
                  srHeroBg.style.backgroundImage = 'none';
                  srHeroBg.style.backgroundColor = '#111827';
              }
              
              if (videoUrl) {
                  srVideoBtn.href = videoUrl;
                  srVideoBtn.style.display = 'inline-flex';
                  srNoVideoBtn.style.display = 'none';
              } else {
                  srVideoBtn.style.display = 'none';
                  srNoVideoBtn.style.display = 'inline-flex';
              }
              
              const tagsHtml = `<span class="sr-tag">${categoryName}</span><span class="sr-tag">Portfolio</span>`;
              srTags.innerHTML = tagsHtml;
          })
          .set(srHeroBg, { scale: 1.05 }) // Initial state before fading in
          .to(srHeroBg, { scale: 1, opacity: 1, duration: 0.6, ease: 'power3.out' })
          .to(srLargeNumber, { opacity: 0.1, x: 0, duration: 0.5, ease: 'power3.out' }, "-=0.4")
          .to(srGlassPanel, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' }, "-=0.4");
    }

    // Attach click listeners
    listItems.forEach(item => {
        item.addEventListener('click', () => selectProject(item));
    });

    // Filtering logic
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            let firstVisible = null;
            
            listItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || filter === category) {
                    item.style.display = 'flex';
                    if (!firstVisible) firstVisible = item;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (firstVisible) {
                isAnimating = false; // Force unlock
                selectProject(firstVisible);
            }
        });
    });

    // Initialize first project (simulate initial animation silently)
    const firstItem = document.querySelector('.showcase-list-item');
    if (firstItem) {
        firstItem.classList.add('active');
        isAnimating = true; // prevent double clicks during init
        
        const thumbUrl = firstItem.getAttribute('data-thumb');
        srLargeNumber.textContent = firstItem.getAttribute('data-index');
        srTitle.textContent = firstItem.getAttribute('data-title');
        srStudent.textContent = firstItem.getAttribute('data-student');
        srCategory.textContent = firstItem.getAttribute('data-category-name');
        srDesc.textContent = firstItem.getAttribute('data-desc');
        
        if (thumbUrl) { srHeroBg.style.backgroundImage = `url('${thumbUrl}')`; }
        
        const videoUrl = firstItem.getAttribute('data-video');
        if (videoUrl) {
            srVideoBtn.href = videoUrl;
            srVideoBtn.style.display = 'inline-flex';
            srNoVideoBtn.style.display = 'none';
        } else {
            srVideoBtn.style.display = 'none';
            srNoVideoBtn.style.display = 'inline-flex';
        }
        
        const tagsHtml = `<span class="sr-tag">${firstItem.getAttribute('data-category-name')}</span><span class="sr-tag">Portfolio</span>`;
        srTags.innerHTML = tagsHtml;
        
        // Initial fade in
        gsap.from(srHeroBg, { opacity: 0, scale: 1.05, duration: 1, ease: 'power3.out' });
        gsap.from(srLargeNumber, { opacity: 0, x: 20, duration: 1, ease: 'power3.out', delay: 0.1 });
        gsap.from(srGlassPanel, { opacity: 0, y: 30, duration: 1, ease: 'power3.out', delay: 0.2, onComplete: () => isAnimating = false });
    }
});
</script>
@endsection
