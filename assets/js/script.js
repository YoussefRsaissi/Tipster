// Prozatím prázdný
console.log("tipsterAi ready");

<script>
const burger = document.querySelector('.burger');
const nav = document.querySelector('nav');
if (burger) {
  burger.addEventListener('click', () => {
    nav.classList.toggle('active');
  });
}
</script>
