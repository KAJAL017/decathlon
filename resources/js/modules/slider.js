/**
 * Slider Module
 * Handles carousel/slider logic for banners and product tracks
 */
export default class Slider {
    constructor(options = {}) {
        this.trackId = options.trackId;
        this.paginationId = options.paginationId;
        this.interval = options.interval || 5000;
        
        this.track = document.getElementById(this.trackId);
        this.pagination = document.getElementById(this.paginationId);
        
        if (!this.track) return;

        this.slides = this.track.children;
        this.slideCount = this.slides.length;
        this.currentIndex = 0;
        this.autoSlideInterval = null;

        this.init();
    }

    init() {
        if (this.pagination) {
            this.pagination.innerHTML = '';
            for (let i = 0; i < this.slideCount; i++) {
                const dot = document.createElement('button');
                dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'}`;
                dot.addEventListener('click', () => this.goToSlide(i));
                this.pagination.appendChild(dot);
            }
        }

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        if (prevBtn) prevBtn.onclick = () => this.goToSlide(this.currentIndex - 1);
        if (nextBtn) nextBtn.onclick = () => this.goToSlide(this.currentIndex + 1);

        this.startAutoSlide();
    }

    goToSlide(index) {
        this.currentIndex = (index + this.slideCount) % this.slideCount;
        this.track.style.transform = `translateX(-${this.currentIndex * 100}%)`;
        
        if (this.pagination) {
            Array.from(this.pagination.children).forEach((dot, i) => {
                dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === this.currentIndex ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'}`;
            });
        }
        
        this.resetAutoSlide();
    }

    startAutoSlide() {
        this.autoSlideInterval = setInterval(() => this.goToSlide(this.currentIndex + 1), this.interval);
    }

    resetAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
            this.startAutoSlide();
        }
    }
}
