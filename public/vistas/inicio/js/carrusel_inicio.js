$(document).ready(function () {
    const splide = new Splide('.splide', {
        type: 'loop',
        height: '32rem',
        perPage: 2,
        breakpoints: {
            640: {
                height: '32rem',
                perPage: 1,
            },
        },
    });
    splide.mount(window.splide.Extensions);
});
