<?php
$closingCtas = [
 'index.php' => ["Let's Make It Happen", 'Your next big idea deserves a great team.', "Tell us where you want to go. We'll help you get there.", 'Start a conversation', '/contact.php'],
 'about.php' => ['Meet the People', 'Get to know the team behind CWT.', 'Meet the specialists who bring our values, experience, and ideas to every project.', 'Meet our crew', '/our-crew.php'],
 'services.php' => ["Let's Work Together", 'Find the right expertise for your next challenge.', 'Tell us what you need. We will help you shape a solution and assemble the right team.', 'Discuss your project', '/contact.php'],
 'products.php' => ["Let's Build", 'Want us to build your solution next?', 'We create and direct elite, full-time engineering teams tailored for high-growth digital products.', 'Talk to our team', '/contact.php'],
 'our-crew.php' => ['Your Next Team', 'Put our people behind your ambition.', 'Work with a dedicated team that brings technical expertise and clear communication to your project.', 'Work with our team', '/contact.php'],
 'clients-say.php' => ['Your Turn', "Let's talk about your project goals.", 'Discover how we can help your team deliver faster and turn your ambitions into a success story.', 'Talk about your goals', '/contact.php'],
 'insights.php' => ["Let's Talk", 'Have a challenge worth solving?', "Our engineers love a good problem. Explore how CWT can help you build what's next.", 'Explore our services', '/services.php'],
 'contact.php' => ['See Our Work', 'Explore what we can build together.', 'Browse the digital solutions we have delivered and find inspiration for your next project.', 'View our products', '/products.php'],
 'privacy-policy.php' => ['Here to Help', 'Have a question about your privacy?', 'Contact our team with questions about how we handle your information.', 'Contact our team', '/contact.php'],
 'project-detail.php' => ['Your Next Success Story', 'Ready to bring your own solution to life?', "Share your goals with us. Let's explore the people and technology your project needs.", 'Discuss your solution', '/contact.php'],
 'insight-detail.php' => ["Let's Build", 'Have a project in mind?', 'We assemble and lead dedicated tech teams that bring ambitious ideas to reality.', 'Talk to our team', '/contact.php'],
];
$closingContent = $closingCtas[basename($_SERVER['SCRIPT_NAME'] ?? '')] ?? $closingCtas['products.php'];
[$closingLabel, $closingTitle, $closingText, $closingButton, $closingPath] = $closingContent;
?>
<section class="prd-cta" id="closing-cta" aria-label="<?= e($closingLabel) ?>">
 <div class="prd-cta-inner">
  <span class="prd-cta-eyebrow"><?= e($closingLabel) ?></span>
  <h2 class="prd-cta-title"><?= e($closingTitle) ?></h2>
  <p class="prd-cta-text"><?= e($closingText) ?></p>
  <a href="<?= e(SITE_URL . $closingPath) ?>" class="prd-cta-btn">
   <span><?= e($closingButton) ?></span>
   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
  </a>
 </div>
</section>
