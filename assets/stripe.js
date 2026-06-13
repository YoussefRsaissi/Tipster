const stripe = Stripe("<?php echo STRIPE_PUBLISHABLE_KEY; ?>");
const elements = stripe.elements();
const cardElement = elements.create("card");
cardElement.mount("#card-element");

const form = document.getElementById("subscription-form");
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const {setupIntent, error} = await stripe.confirmCardSetup(
    "<?php echo $clientSecret; ?>", {
      payment_method: { card: cardElement }
    }
  );

  if(error){
    alert(error.message);
  } else {
    const plan = document.getElementById("plan").value;
    const res = await fetch("create-subscription.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({plan: plan, paymentMethod: setupIntent.payment_method})
    });
    const data = await res.json();
    if(data.success){
      window.location.href = "success.php";
    } else {
      window.location.href = "cancel.php";
    }
  }
});
