document.addEventListener('DOMContentLoaded', () => {
    
    // Check if GSAP is loaded
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);



        // Staggered reveal for grid cards
        const grids = [
            '.aksha-info-grid', 
            '.aksha-showcase-grid',
            '.aksha-locations-grid',
            '.aksha-stats-row',
            '.aksha-roles-grid'
        ];

        grids.forEach(gridSelector => {
            const grid = document.querySelector(gridSelector);
            if(grid) {
                const cards = grid.children;
                gsap.fromTo(cards, 
                    {
                        y: 30,
                        opacity: 0
                    },
                    {
                        scrollTrigger: {
                            trigger: grid,
                            start: "top 85%",
                            toggleActions: "play none none reverse"
                        },
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.15,
                        ease: "power2.out"
                    }
                );
            }
        });
    }

    
    function injectSuccessStyles() {
        if (document.getElementById('enquiry-success-styles')) return;

        var css =
            '.enquiry-success-state {' +
                'text-align: center;' +
                'padding: 40px 20px;' +
                'color: #ffffff;' +
                'display: flex;' +
                'flex-direction: column;' +
                'align-items: center;' +
                'justify-content: center;' +
                'min-height: 200px;' +
            '}' +
            '.enquiry-success-state .success-checkmark {' +
                'margin-bottom: 8px;' +
            '}' +
            '.enquiry-success-state h3 {' +
                'font-family: inherit;' +
                'font-size: 1.5rem;' +
                'margin-top: 20px;' +
                'color: #ffd700;' +
                'font-weight: 600;' +
                'letter-spacing: 0.5px;' +
            '}' +
            '.enquiry-success-state p {' +
                'font-family: inherit;' +
                'color: #9999bb;' +
                'margin-top: 8px;' +
                'font-size: 1rem;' +
                'line-height: 1.5;' +
            '}';

        var style = document.createElement('style');
        style.id = 'enquiry-success-styles';
        style.type = 'text/css';
        style.textContent = css;
        document.head.appendChild(style);
    }
    injectSuccessStyles();

    // AJAX Form Submission
    var form = document.getElementById('akshaEnquiryForm');
    if (form) {
        var submitBtn = form.querySelector('.enquiry-submit');
        var btnText  = submitBtn ? submitBtn.querySelector('.btn-text') : null;
        var btnArrow = submitBtn ? submitBtn.querySelector('.submit-arrow') : null;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // CSRF token
            var tokenMeta = document.querySelector('meta[name="csrf-token"]');
            var token = tokenMeta ? tokenMeta.content : '';

            if (submitBtn) submitBtn.disabled = true;
            if (btnText)  btnText.textContent = 'Submitting...';
            if (btnArrow) btnArrow.style.display = 'none';

            // Clear previous validation errors
            form.querySelectorAll('.field-error').forEach(function (el) { el.textContent = ''; });

            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 0 && data.error) {
                    Object.keys(data.error).forEach(function (key) {
                        var errorEl = form.querySelector('.' + key + '_error');
                        if (errorEl) {
                            errorEl.textContent = data.error[key][0];
                            var field = errorEl.closest('.enquiry-field');
                            if (field && typeof gsap !== 'undefined') {
                                gsap.fromTo(field, { x: -8 }, { x: 0, duration: 0.5, ease: 'elastic.out(1, 0.3)' });
                            }
                        }
                    });
                } else if (data.status === 1) {
                    var formCard = form.closest('.maac-enquiry-form');
                    if (!formCard) formCard = form.parentElement;
                    gsap.to(form, { opacity: 0, y: -20, duration: 0.4, onComplete: function () {
                        form.style.display = 'none';
                        var successDiv = document.createElement('div');
                        successDiv.className = 'enquiry-success-state';
                        successDiv.innerHTML = '<div class="success-checkmark"><svg viewBox="0 0 100 100" width="80" height="80"><circle class="success-circle" cx="50" cy="50" r="45" fill="none" stroke="url(#akshaGoldGrad)" stroke-width="3"/><path class="success-check" d="M30 52 L44 66 L70 36" fill="none" stroke="url(#akshaGoldGrad)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><defs><linearGradient id="akshaGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#ffd700"/><stop offset="100%" style="stop-color:#ff6a00"/></linearGradient></defs></svg></div><h3>Thank You! 🎉</h3><p>Our counsellor will contact you shortly.</p>';
                        formCard.appendChild(successDiv);
                        gsap.fromTo(successDiv, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' });
                    }});
                }
            })
            .catch(err => {
                console.error('Form submission error:', err);
                alert('Something went wrong. Please try again.');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
                if (btnText)  btnText.textContent = 'START MY JOURNEY';
                if (btnArrow) btnArrow.style.display = '';
            });
        });
    }

});
