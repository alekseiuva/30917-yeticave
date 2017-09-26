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
    action="sign-up.php"
    method="post"
    enctype="multipart/form-data"
  >
    <h2>Регистрация нового аккаунта</h2>

    <div
        class="<?= isset($formErrors['email']) ?
            'form__item form__item--invalid' :
            'form__item'; ?>"
    >
      <label for="email">E-mail*</label>
      <input
        id="email"
        type="text"
        name="email"
        placeholder="Введите e-mail"
        value="<?= isset($newUser['email']) ? htmlspecialchars($newUser['email']) : ''; ?>">
      <span class="form__error">
          <?= $formErrors['email'] ?? ''; ?>
      </span>
    </div>

    <div
        class="<?= isset($formErrors['password']) ?
            'form__item form__item--invalid' :
            'form__item'; ?>"
    >
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="password" placeholder="Введите пароль">
      <span class="form__error">
        <?= $formErrors['password'] ?? ''; ?>
      </span>
    </div>

    <div
        class="<?= isset($formErrors['name']) ?
            'form__item form__item--invalid' :
            'form__item'; ?>"
    >
      <label for="name">Имя*</label>
      <input
        id="name"
        type="text"
        name="name"
        placeholder="Введите имя"
        value="<?= isset($newUser['name']) ? htmlspecialchars($newUser['name']) : ''; ?>"
    >
      <span class="form__error">
        <?= $formErrors['name'] ?? ''; ?>
      </span>
    </div>

    <div class="form__item">
      <label for="contacts_info">Контактные данные*</label>
      <textarea
        id="contacts_info"
        name="contacts_info"
        placeholder="Напишите как с вами связаться"
    ><?= isset($newUser['contacts_info']) ? htmlspecialchars($newUser['contacts_info']) : ''; ?></textarea>
      <span class="form__error">
        <?= $formErrors['contacts_info'] ?? ''; ?>
      </span>
    </div>

    <div
        class="<?= is_null($newUser['avatar']) ?
            'form__item form__item--file' :
            'form__item form__item--uploaded form__item--file'?>"
    >
      <label>Изображение</label>
      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img
            src="<?= $newUser['avatar'] ?? 'img/no-avatar.jpg' ?>"
            width="113"
            height="113"
            alt="Аватар пользователя"
        >
        </div>
      </div>

      <div class="form__input-file">
        <input
            class="visually-hidden"
            name="avatar"
            type="file"
            id="photo2"
            value="<?= $newUser['avatar'] ?>"
        >
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
      <span class="form__error form__error--visible">
        <?= $formErrors['avatar'] ?? ''?>
      </span>
    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
  </form>
