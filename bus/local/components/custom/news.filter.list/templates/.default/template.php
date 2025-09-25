<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="news-filter-component">
  <form method="get" class="news-filter-form">
    <div class="row">
      <label>Поиск по названию:
        <input type="text" name="q" value="<?=htmlspecialchars($arResult['FILTER']['q'])?>">
      </label>
    </div>
    <div class="row">
      <label>Стоимость (COST):
        <input type="number" name="cost" min="0" step="1" value="<?=htmlspecialchars($arResult['FILTER']['cost'])?>">
      </label>
    </div>
    <div class="row">
      <label>Период:
        <select name="period">
          <option value="all"   <?= $arResult['FILTER']['period']==='all'?'selected':'' ?>>За всё время</option>
          <option value="month" <?= $arResult['FILTER']['period']==='month'?'selected':'' ?>>За этот месяц</option>
          <option value="week"  <?= $arResult['FILTER']['period']==='week'?'selected':'' ?>>За эту неделю</option>
        </select>
      </label>
    </div>
    <div class="row"><button type="submit">Применить</button></div>
  </form>

  <?php if (empty($arResult['ITEMS'])): ?>
    <div class="empty">Ничего не найдено.</div>
  <?php else: ?>
    <ul class="news-list">
      <?php foreach ($arResult['ITEMS'] as $it): ?>
        <li class="news-item">
          <div class="title">
            <?php if ($it['DETAIL_PAGE_URL']): ?>
              <a href="<?=$it['DETAIL_PAGE_URL']?>"><?=htmlspecialchars($it['NAME'])?></a>
            <?php else: ?>
              <?=htmlspecialchars($it['NAME'])?>
            <?php endif; ?>
          </div>
          <div class="meta">
            <span class="date"><?=$it['DATE_ACTIVE_FROM']?></span>
            <span class="cost">COST: <?=htmlspecialchars($it['COST'])?></span>
            <span class="feature">FEATURE: <?=htmlspecialchars($it['FEATURE'])?></span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<style>
.news-filter-component{padding:12px;border:1px solid #e3e3e3;border-radius:8px}
.news-filter-form .row{margin-bottom:8px}
.news-list{list-style:none;padding:0;margin:12px 0 0}
.news-item{padding:8px 0;border-bottom:1px solid #eee}
.news-item .title{font-weight:600;margin-bottom:4px}
.news-item .meta{font-size:12px;opacity:.8;display:flex;gap:10px;flex-wrap:wrap}
.empty{padding:8px;opacity:.7}
</style>
