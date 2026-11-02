<?php
require __DIR__ . '/data.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function has_file(?string $path): bool
{
    return $path && is_file(__DIR__ . '/' . $path);
}

function photo(string $path, string $alt): string
{
    if (has_file($path)) {
        return '<img src="' . e($path) . '" alt="' . e($alt) . '" loading="lazy">';
    }
    return '<div class="placeholder"><span>' . e($alt) . '<small>' . e($path) . '</small></span></div>';
}

function media(array $item): string
{
    $path    = !empty($item['video']) ? $item['video'] : ($item['media'] ?? '');
    $isVideo = !empty($item['video']) || ($item['type'] ?? 'image') === 'video';

    if ($isVideo && has_file($path)) {
        return '<video controls preload="metadata" playsinline src="' . e($path) . '"></video>';
    }
    return photo($item['media'] ?? '', $item['title'] ?? 'Media');
}

function card_media(array $p): string
{
    $title = $p['title'] ?? 'Media';

    if (!empty($p['thumb'])) {
        return photo($p['thumb'], $title);
    }
    if (($p['type'] ?? 'image') === 'video' && has_file($p['media'] ?? '')) {
        return '<video muted playsinline preload="metadata" src="' . e($p['media']) . '#t=0.5"></video>';
    }
    return photo($p['media'] ?? '', $title);
}

function chips(array $items): string
{
    $html = '<ul class="chips">';
    foreach ($items as $item) {
        $html .= '<li>' . e($item) . '</li>';
    }
    return $html . '</ul>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($site['name']) ?> — <?= e(ucwords(strtolower($site['role']))) ?></title>
    <meta name="description" content="Portfolio of <?= e($site['name']) ?>, multimedia creative.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;500;700&family=Newsreader:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>document.documentElement.classList.add('js');</script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- social media -->
<nav class="top-links" aria-label="Social links">
    <a href="mailto:<?= e($site['email']) ?>" aria-label="Email"><i class="fa-solid fa-envelope"></i></a>
    <a href="<?= e($site['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
    <a href="<?= e($site['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="<?= e($site['youtube']) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
</nav>

<!-- menu -->
<input type="checkbox" id="menu-toggle" class="menu-toggle">
<label for="menu-toggle" class="menu-btn" aria-label="Toggle menu"><span></span><span></span><span></span></label>
<nav class="side-nav" aria-label="Sections">
    <?php foreach ($nav as $id => $label): ?>
        <a href="#<?= e($id) ?>" data-target="<?= e($id) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
</nav>


<!-- hero -->
<header class="hero" id="top">
    <?php if (has_file($site['hero_img'])): ?>
        <img class="hero-img" src="<?= e($site['hero_img']) ?>" alt="<?= e($site['name']) ?>">
    <?php else: ?>
        <div class="hero-img hero-placeholder"><span>Add your photo at <?= e($site['hero_img']) ?></span></div>
    <?php endif; ?>
    <div class="hero-shade"></div>
    <div class="hero-text">
        <h1><span><?= e($site['first']) ?></span><span><?= e($site['last']) ?></span></h1>
        <p class="hero-role"><?= e($site['role']) ?></p>
    </div>
</header>

<main>

<!-- about -->
<section id="about" class="section">
    <div class="wrap">
        <h2 class="eyebrow">About Me</h2>
        <?php foreach ($about as $i => $block): ?>
            <div class="split<?= $i % 2 ? ' reverse' : '' ?>">
                <figure><?= photo($block['img'], 'About photo ' . ($i + 1)) ?></figure>
                <p class="split-text"><?= e($block['text']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- what-i-do -->
<section id="what-i-do" class="section alt">
    <div class="wrap">
        <h2 class="eyebrow">What I Do</h2>
        <div class="skills">
            <?php foreach ($skills as $n => $skill): ?>
                <article class="skill">
                    <span class="skill-num"><?= str_pad($n + 1, 2, '0', STR_PAD_LEFT) ?></span>
                    <div>
                        <h3><?= e($skill['title']) ?></h3>
                        <p><?= e($skill['desc']) ?></p>
                        <p class="tools"><?= e(implode(' · ', $skill['tools'])) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- experience -->
<section id="experience" class="section">
    <div class="wrap">
        <h2 class="eyebrow">Creative Experience</h2>

        <?php foreach ($films as $film): ?>
            <article class="film">
                <div class="film-media"><?= media($film) ?></div>
                <p class="film-tag"><?= e($film['tag']) ?></p>
                <h3 class="film-title"><?= e($film['title']) ?></h3>
                <p class="film-roles"><?= e($film['roles']) ?></p>
                <p class="film-desc"><?= e($film['desc']) ?></p>

                <?php if (!empty($film['awards'])): $awards = $film['awards']; ?>
                    <div class="awards">
                        <h4 class="eyebrow small">Awards &amp; Recognition</h4>
                        <div class="award-highlight">
                            <?= photo($awards['highlight']['img'], 'Award photo') ?>
                            <div>
                                <h5><?= e($awards['highlight']['title']) ?></h5>
                                <p><?= e($awards['highlight']['desc']) ?></p>
                            </div>
                        </div>

                        <h4 class="eyebrow small">Featured Awards</h4>
                        <ul class="award-grid">
                            <?php foreach ($awards['featured'] as [$rank, $name]): ?>
                                <li><strong><?= e($rank) ?></strong><span><?= e($name) ?></span></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if (!empty($awards['special'])): ?>
                            <div class="award-special">
                                <strong><?= e($awards['special'][0]) ?></strong>
                                <span><?= e($awards['special'][1]) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- projects -->
<section id="projects" class="section alt">
    <div class="wrap">
        <h2 class="eyebrow">Selected Projects</h2>

        <?php $pid = 0; foreach ($projects as $category => $items): ?>
            <div class="category">
                <h3 class="category-title"><?= e($category) ?></h3>
                <div class="cards">
                    <?php foreach ($items as $p): $pid++; $mid = 'project-' . $pid; ?>

                        <!-- card: photo, title, short description, tools -->
                        <article class="card" role="button" tabindex="0" data-modal="<?= $mid ?>" aria-haspopup="dialog">
                            <div class="card-media"><?= card_media($p) ?></div>
                            <div class="card-body">
                                <h4><?= e($p['title']) ?></h4>
                                <p><?= e($p['short'] ?? '') ?></p>
                                <?php if (!empty($p['software'])) echo chips($p['software']); ?>
                            </div>
                        </article>

                        <!-- modal: full details -->
                        <dialog class="modal" id="<?= $mid ?>" aria-labelledby="<?= $mid ?>-title">
                            <button type="button" class="modal-close" data-close aria-label="Close">&times;</button>
                            <div class="modal-grid">
                                <div class="modal-media"><?= media($p) ?></div>
                                <div class="modal-info">
                                    <p class="modal-cat"><?= e($category) ?></p>
                                    <h3 id="<?= $mid ?>-title"><?= e($p['title']) ?></h3>

                                    <h5>Overview</h5>
                                    <p><?= e($p['overview'] ?? ($p['short'] ?? '')) ?></p>

                                    <?php if (!empty($p['role'])): ?>
                                        <h5>Project Role</h5>
                                        <p><?= e($p['role']) ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($p['software'])): ?>
                                        <h5>Tools Used</h5>
                                        <?= chips($p['software']) ?>
                                    <?php endif; ?>

                                    <?php if (!empty($p['awards'])): ?>
                                        <h5>Awards &amp; Recognition</h5>
                                        <ul class="modal-awards">
                                            <?php foreach ($p['awards'] as $award): ?><li><?= e($award) ?></li><?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <?php if (!empty($p['link'])): ?>
                                        <a class="btn" href="<?= e($p['link']) ?>" target="_blank" rel="noopener"><?= e($p['btn'] ?? 'View Work') ?> &rarr;</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </dialog>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- education -->
<section id="education" class="section">
    <div class="wrap">
        <h2 class="eyebrow">Education</h2>
        <?php foreach ($education as $i => $entry): ?>
            <div class="split<?= $i % 2 ? ' reverse' : '' ?>">
                <figure><?= photo($entry['img'], $entry['level']) ?></figure>
                <div class="split-text">
                    <p class="edu-level"><?= e($entry['level']) ?> · <?= e($entry['years']) ?></p>
                    <h3><?= e($entry['school']) ?></h3>
                    <p class="edu-course"><?= e($entry['course']) ?></p>
                    <p><?= e($entry['text']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- contact -->
<section id="contact" class="section alt">
    <div class="wrap contact">
        <h2 class="eyebrow">Contact</h2>
        <p class="contact-lead">Let's make something together.</p>
        <ul class="contact-list">
            <li><span>Email</span><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li>
            <?php if ($site['phone']): ?>
                <li><span>Phone</span><a href="tel:<?= e(preg_replace('/\s+/', '', $site['phone'])) ?>"><?= e($site['phone']) ?></a></li>
            <?php endif; ?>
            <li><span>Location</span><?= e($site['location']) ?></li>
            <li><span>Messenger</span><a href="<?= e($site['messenger']) ?>" target="_blank" rel="noopener"><?= e($site['name']) ?></a></li>
            <li><span>LinkedIn</span><a href="<?= e($site['linkedin']) ?>" target="_blank" rel="noopener"><?= e($site['name']) ?></a></li>
            <li><span>Telegram</span><a href="<?= e($site['telegram']) ?>" target="_blank" rel="noopener"><?= e($site['name']) ?></a></li>
        </ul>
    </div>
</section>

</main>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= e($site['name']) ?>. All rights reserved.</p>
</footer>


<script>
(() => {
    'use strict';

    const root = document.documentElement;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;


    /*side-menu highliht*/
    const navLinks = document.querySelectorAll('.side-nav a');
    const linkFor  = {};
    navLinks.forEach(a => { linkFor[a.dataset.target] = a; });

    const sectionObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting || !linkFor[entry.target.id]) return;
            navLinks.forEach(a => a.classList.remove('active'));
            linkFor[entry.target.id].classList.add('active');
        });
    }, { rootMargin: '-40% 0px -55% 0px' });

    document.querySelectorAll('main section').forEach(s => sectionObserver.observe(s));
    navLinks.forEach(a => a.addEventListener('click', () => {
        document.getElementById('menu-toggle').checked = false;
    }));


    /*modals*/
    function closeModal(dialog) {
        if (dialog.classList.contains('closing')) return;
        if (reduceMotion) { dialog.close(); return; }

        dialog.classList.add('closing');
        setTimeout(() => {
            dialog.classList.remove('closing');
            dialog.close();
        }, 250);
    }

    document.querySelectorAll('.card[data-modal]').forEach(card => {
        const open = () => document.getElementById(card.dataset.modal).showModal();
        card.addEventListener('click', open);
        card.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
        });
    });

    document.querySelectorAll('dialog.modal').forEach(dialog => {
        dialog.addEventListener('click', e => {
            if (e.target === dialog || e.target.closest('[data-close]')) closeModal(dialog);
        });
        dialog.addEventListener('cancel', e => {
            e.preventDefault();
            closeModal(dialog);
        });
        dialog.addEventListener('close', () => {
            dialog.querySelectorAll('video').forEach(v => v.pause());
        });
    });


    /*scroll*/
    if (!('IntersectionObserver' in window) || reduceMotion) {
        root.classList.remove('js');
        return;
    }

    const revealSelector = [
        '.eyebrow', '.split figure', '.split-text', '.skill',
        '.film-media', '.film-tag', '.film-title', '.film-roles', '.film-desc',
        '.award-highlight', '.award-grid li', '.award-special',
        '.category-title', '.card', '.contact-lead', '.contact-list li',
    ].join(', ');

    const siblingCount = new Map();
    const revealObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('in');
            revealObserver.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    document.querySelectorAll(revealSelector).forEach(el => {
        const index = siblingCount.get(el.parentNode) || 0;
        siblingCount.set(el.parentNode, index + 1);
        el.style.setProperty('--d', (index % 4) * 0.1 + 's');

        el.classList.add('reveal');
        el.addEventListener('transitionend', e => {
            if (e.target === el && e.propertyName === 'transform') {
                el.classList.remove('reveal', 'in');
                el.style.removeProperty('--d');
            }
        });
        revealObserver.observe(el);
    });
})();
</script>
</body>
</html>