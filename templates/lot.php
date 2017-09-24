<nav class="nav">
  <ul class="nav__list container">
  <?php foreach($categories as $category): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>

<section class="lot-item container">
    <h2><?=$lot['name']?></h2>

    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $categories[$lot['category_id']]['name']; ?></span></p>
            <p class="lot-item__description">
                <?= htmlspecialchars($lot['description']); ?>
            </p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?= formatRemaingTime($lot['date_expires']); ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= $lot['price_start']; ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= $lot['bet_step']; ?>р</span>
                    </div>
                </div>

                <?php if ($is_auth && !$isBetMade): ?>
                    <form
                        class="<?= count($formErrors) > 0 ? 'lot-item__form lot-item__form--invalid' : 'lot-item__form' ?>"
                        action="<?='lot.php?id=' . $lotId; ?>"
                        method="post"
                    >
                        <p class="lot-item__form-item">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="price" placeholder="<?= $lot['price_start'] + $lot['bet_step']; ?>">
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                        <span class="form__error"><?= $formErrors['cost'] ?? ''; ?></span>
                    </form>
                <?php endif; ?>

                </div>
            <div class="history">
                <h3>История ставок (<span>4</span>)</h3>
                <!-- заполните эту таблицу данными из массива $bets-->
                <table class="history__list">
                <?php foreach($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= htmlspecialchars($bet['name']); ?></td>
                        <td class="history__price"><?= htmlspecialchars($bet['price']); ?> ₽</td>
                        <td class="history__time"><?= formatTime($bet['ts']);  ?></td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>

