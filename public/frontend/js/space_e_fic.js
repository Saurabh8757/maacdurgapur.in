document.addEventListener('DOMContentLoaded', () => {
    
    // Check if GSAP is loaded
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        // Premium Subtle Fade Up Reveal
        const revealElements = document.querySelectorAll('.gsap-reveal');
        
        revealElements.forEach((el) => {
            gsap.fromTo(el, 
                { 
                    y: 40, 
                    opacity: 0 
                },
                {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%", // Triggers slightly before element comes into full view
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 1,
                    ease: "power2.out"
                }
            );
        });

        // Staggered reveal for grid cards (Projects, Info, Locations)
        const grids = [
            '.sef-info-grid', 
            '.sef-showcase-grid', 
            '.sef-locations-grid'
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

});
