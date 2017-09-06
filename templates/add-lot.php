<nav class="nav">
  <ul class="nav__list container">

  <?php foreach($categories as $category): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?=htmlspecialchars($category);?></a>
    </li>
  <?php endforeach; ?>

  </ul>
</nav>

<form
    class="<?= count($formErrors) > 0 ? 'form form--invalid container' : 'form container' ?>"
    action="add.php"
    method="post"
    enctype="multipart/form-data"
>
    <h2>Добавление лота</h2>

    <div class="form__container-two">
        <div class="<?= isset($formErrors['lot-name']) ? 'form__item form__item--invalid' : 'form__item' ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required value="<?= htmlspecialchars($lotName); ?>">
            <span class="form__error">
                <?= $formErrors['lot-name'] ?? ''?>
            </span>
        </div>

        <div class="form__item">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <?php foreach($categories as $category): ?>
                    <option <?= $lotCategory === $category ? 'selected' : '' ?> >
                        <?=htmlspecialchars($category);?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">
                <?= $formErrors['category'] ?? ''?>
            </span>
        </div>
    </div>

    <div class="<?= isset($formErrors['message']) ? 'form__item form__item--wide form__item--invalid' : 'form__item form__item--wide' ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= htmlspecialchars($message); ?></textarea>
        <span class="form__error">
            <?= $formErrors['message'] ?? ''?>
        </span>
    </div>

    <div class="<?= is_null($lotImage) ? 'form__item form__item--file' : 'form__item form__item--uploaded form__item--file'?>">
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?= $lotImage ?? '' ?>" width="113" height="113" alt="Изображение лота">
            </div>
        </div>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="<?= $lotImage ?>" name="lot-image">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>

    <div class="form__container-three">

        <div class="<?= isset($formErrors['lot-rate']) ? 'form__item form__item--small form__item--invalid' : 'form__item form__item--small' ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" value="<?= htmlspecialchars($lotRate) ?>" placeholder="0" required>
            <span class="form__error">
                <?= $formErrors['lot-rate'] ?? ''?>
            </span>
        </div>

        <div class="<?= isset($formErrors['lot-step']) ? 'form__item form__item--small form__item--invalid' : 'form__item form__item--small' ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" value="<?= htmlspecialchars($lotStep) ?>" placeholder="0" required>
            <span class="form__error">
                <?= $formErrors['lot-step'] ?? ''?>
            </span>
        </div>

        <div class="<?= isset($formErrors['lot-date']) ? 'form__item form__item--invalid' : 'form__item' ?>">
            <label for="lot-date">Дата завершения</label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?= htmlspecialchars($lotDate) ?>" placeholder="20.05.2017" required>
            <span class="form__error">
                <?= $formErrors['lot-date'] ?? ''?>
            </span>
        </div>

    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>

