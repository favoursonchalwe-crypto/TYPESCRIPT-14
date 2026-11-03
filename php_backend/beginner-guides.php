<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Beginner-friendly urban farming guides from Green Urban Farms.">
    <title>Beginner Guides | Green Urban Farms</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <section class="hero page-banner page-banner-resources-detail">
            <section class="container"><span class="badge">GREEN URBAN FARMS LEARNING CENTRE</span>
                <h1>Beginner Guides</h1>
                <p class="lead">New to urban farming? Start small, observe your plants and build your skills step by step.</p>
                <section class="actions"><a class="btn btn-primary" href="growing-tips.php">Explore Growing Tips</a><a class="btn btn-secondary" href="resources.php">Back to Resources</a></section>
            </section>
        </section>
        <section class="section">
            <section class="container content">
                <h2>Start your urban garden with confidence</h2>
                <p>Urban food growing can begin with a few containers and a suitable sunny spot. Use these simple steps to establish a healthy, manageable garden.</p>
                <section class="tips-grid guide-grid">
                    <article class="tip-panel"><span class="tip-number">01</span>
                        <h3>Choose a suitable growing area</h3>
                        <p>Look for a balcony, courtyard, rooftop, doorstep or windowsill that receives suitable sunlight and is easy to reach for watering and care.</p>
                    </article>
                    <article class="tip-panel"><span class="tip-number">02</span>
                        <h3>Select containers with drainage</h3>
                        <p>Use clean pots, buckets or grow bags that are safe for food production. Make sure excess water can drain freely to protect plant roots.</p>
                    </article>
                    <article class="tip-panel"><span class="tip-number">03</span>
                        <h3>Start with easy crops</h3>
                        <p>Try spinach, lettuce, rape, spring onions and herbs. Choose crops that suit the space, season and amount of sunlight available.</p>
                    </article>
                    <article class="tip-panel"><span class="tip-number">04</span>
                        <h3>Prepare healthy soil</h3>
                        <p>Use a light, well-draining growing mix enriched with mature compost. Avoid soil from places that may be contaminated.</p>
                    </article>
                    <article class="tip-panel"><span class="tip-number">05</span>
                        <h3>Water and observe regularly</h3>
                        <p>Check the soil often, water when the surface begins to dry and watch for changes in leaf colour, pests or plant growth.</p>
                    </article>
                    <article class="tip-panel"><span class="tip-number">06</span>
                        <h3>Learn as you grow</h3>
                        <p>Keep simple notes about planting dates, watering and harvests. Make small improvements and expand your garden gradually.</p>
                    </article>
                </section>
                <section class="callout">
                    <h3>Take the next step</h3>
                    <p>Explore our programmes or contact us to learn how to participate in community growing activities.</p><a class="btn btn-primary" href="get-involved.php">Get Involved</a> <a class="btn btn-blue" href="contact.php">Contact Us</a>
                </section>
            </section>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>