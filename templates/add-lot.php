<nav class="nav">
  <ul class="nav__list container">

  <?php foreach($categories as $category): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?=htmlspecialchars($category['name']);?></a>
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
        <div class="<?= isset($formErrors['name']) ? 'form__item form__item--invalid' : 'form__item' ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" required value="<?= htmlspecialchars($lot['name']); ?>">
            <span class="form__error">
                <?= $formErrors['name'] ?? ''?>
            </span>
        </div>

        <div class="form__item">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <?php foreach($categories as $key => $category): ?>
                    <option
                        <?= $lot['category'] === $key ? 'selected' : '' ?>
                        value="<?= $key ?>"
                    >
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">
                <?= $formErrors['category'] ?? ''?>
            </span>
        </div>
    </div>

    <div class="<?= isset($formErrors['description']) ? 'form__item form__item--wide form__item--invalid' : 'form__item form__item--wide' ?>">
        <label for="description">Описание</label>
        <textarea id="description" name="description" placeholder="Напишите описание лота" required><?= htmlspecialchars($lot['description']); ?></textarea>
        <span class="form__error">
            <?= $formErrors['description'] ?? ''?>
        </span>
    </div>

    <div class="<?= is_null($lot['image']) ? 'form__item form__item--file' : 'form__item form__item--uploaded form__item--file'?>">
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?= $lot['image'] ?? '' ?>" width="113" height="113" alt="Изображение лота">
            </div>
        </div>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="<?= $lot['image'] ?>" name="image">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>

    <div class="form__container-three">

        <div class="<?= isset($formErrors['price']) ? 'form__item form__item--small form__item--invalid' : 'form__item form__item--small' ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="price" value="<?= htmlspecialchars($lot['price']) ?>" placeholder="0" required>
            <span class="form__error">
                <?= $formErrors['price'] ?? ''?>
            </span>
        </div>

        <div class="<?= isset($formErrors['step']) ? 'form__item form__item--small form__item--invalid' : 'form__item form__item--small' ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="step" value="<?= htmlspecialchars($lot['step']) ?>" placeholder="0" required>
            <span class="form__error">
                <?= $formErrors['step'] ?? ''?>
            </span>
        </div>

        <div class="<?= isset($formErrors['date']) ? 'form__item form__item--invalid' : 'form__item' ?>">
            <label for="lot-date">Дата завершения</label>
            <input class="form__input-date" id="lot-date" type="text" name="date" value="<?= htmlspecialchars($lot['date']) ?>" placeholder="20.05.2017" required>
            <span class="form__error">
                <?= $formErrors['date'] ?? ''?>
            </span>
        </div>

    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>

