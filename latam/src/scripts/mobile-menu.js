const burgerMenu = document.querySelector('.burger-menu');
const mobileMenu = document.querySelector('.mobile-menu');
const logosContainer = document.querySelector('.logos-container');
const nav = document.querySelector('nav');

burgerMenu.addEventListener('click', () => {
  burgerMenu.classList.toggle('active');
  mobileMenu.classList.toggle('active');
  logosContainer.classList.toggle('active-logo');
  nav.classList.toggle('mobile-active');
  
  if (mobileMenu.classList.contains('active')) {
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
  } else {
    document.body.style.overflow = 'auto';
    document.body.style.position = 'relative';
    document.body.style.width = 'auto';
  }
});

window.toggleDropdown = function(dropdownId) {
  const solutionsDropdown = document.getElementById('solutions-dropdown');
  const officesDropdown = document.getElementById('offices-dropdown');
  
  if (dropdownId === 'solutions-dropdown') {
    solutionsDropdown.classList.toggle('active');
    officesDropdown.classList.remove('active-offices');
  } else if (dropdownId === 'offices-dropdown') {
    officesDropdown.classList.toggle('active-offices');
    solutionsDropdown.classList.remove('active');
  }
};

const mobileLinks = document.querySelectorAll('.mobile-menu a');
mobileLinks.forEach(link => {
  link.addEventListener('click', () => {
    burgerMenu.classList.remove('active');
    mobileMenu.classList.remove('active');
    logosContainer.classList.remove('active');
    nav.classList.remove('mobile-active');
    document.body.style.overflow = 'auto';
    document.body.style.position = 'relative';
    document.body.style.width = 'auto';
  });
});