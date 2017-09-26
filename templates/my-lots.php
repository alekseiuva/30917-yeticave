<nav class="nav">

    <ul class="nav__list container">
    <?php foreach($categories as $category): ?>
        <li class="nav__item">
            <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
  </nav>

  <section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach($bets as $bet): ?>
        <tr class="<?= $bet['status'] === 'win' ? 'rates__item rates__item--win' : 'rates__item'; ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?= $bet['image']; ?>" width="54" height="40" alt="<?= $categories[$bet['category_id']]['name']; ?>">
                </div>
                <div>
                    <h3 class="rates__title">
                        <a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= htmlspecialchars($bet['name']); ?></a>
                    </h3>
                    <?php if ($bet['status'] === 'win'): ?>
                    <p><?= htmlspecialchars($bet['info']); ?></p>
                    <?php endif; ?>
                </div>
            </td>

            <td class="rates__category"><?= getCategoryName($lot['category_id'], $categories) ?></td>
            <td class="rates__timer">
                <div class="<?= "timer timer--" . $bet['status']; ?>">
                    <!-- TODO: fix it when all values are stored in db in the same format -->
                    <?= formatRemaingTime($bet['date_expires']); ?>
                </div>
            </td>
            <td class="rates__price"><?= htmlspecialchars($bet['price']); ?> ₽</td>
            <td class="rates__time"><?= formatTime($bet['date_start']); ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
  </section>
