<?php

/**
 * 友链
 *
 * @package custom
 *
 **/
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('module/head.php'); ?>
	<?php if (!empty($this->options->JPrismTheme)) : ?>
		<link rel="stylesheet" href="<?= \Joe\theme_url('assets/plugin/prism/themes/' . $this->options->JPrismTheme) ?>">
	<?php else : ?>
		<link rel="stylesheet" href="//cdn.staticfile.org/prism/1.23.0/themes/prism.min.css">
	<?php endif; ?>
	<script src="//cdn.staticfile.org/clipboard.js/2.0.6/clipboard.min.js"></script>
	<script src="<?= joe\theme_url('assets/plugin/prism/prism.min.js') ?>"></script>
	<script src="<?= joe\theme_url('assets/js/joe.post_page.js'); ?>"></script>
</head>

<body>
	 <div id="Joe">
        <?php $this->need('module/header.php'); ?>
        <div class="joe_container">
            <div class="joe_main">
                <div class="joe_detail" data-cid="<?php echo $this->cid ?>">
                    <?php $this->need('module/batten.php'); ?>
                    <?php $this->need('module/article.php'); ?>

                    <?php
                    $friends = [];
                    $friends_color = [
                        '#F8D800',
                        '#0396FF',
                        '#EA5455',
                        '#7367F0',
                        '#32CCBC',
                        '#F6416C',
                        '#28C76F',
                        '#9F44D3',
                        '#F55555',
                        '#736EFE',
                        '#E96D71',
                        '#DE4313',
                        '#D939CD',
                        '#4C83FF',
                        '#F072B6',
                        '#C346C2',
                        '#5961F9',
                        '#FD6585',
                        '#465EFB',
                        '#FFC600',
                        '#FA742B',
                        '#5151E5',
                        '#BB4E75',
                        '#FF52E5',
                        '#49C628',
                        '#00EAFF',
                        '#F067B4',
                        '#F067B4',
                        '#ff9a9e',
                        '#00f2fe',
                        '#4facfe',
                        '#f093fb',
                        '#6fa3ef',
                        '#bc99c4',
                        '#46c47c',
                        '#f9bb3c',
                        '#e8583d',
                        '#f68e5f',
                    ];
                    $friends_text = $this->options->JFriends;
                    if ($friends_text) {
                        $friends_arr = explode("\r\n", $friends_text);
                        if (count($friends_arr) > 0) {
                            for ($i = 0; $i < count($friends_arr); $i++) {
                                $name = explode("||", $friends_arr[$i])[0] ?? '';
                                $url = explode("||", $friends_arr[$i])[1] ?? '';
                                $avatar = explode("||", $friends_arr[$i])[2] ?? '';
                                $desc = explode("||", $friends_arr[$i])[3] ?? '';
                                $friends[] = array("name" => trim($name), "url" => trim($url), "avatar" => trim($avatar), "desc" => trim($desc));
                            };
                        }
                    }
                    ?>
                    <?php if (sizeof($friends) > 0) : ?>
                        <ul class="joe_detail__friends">
                            <?php
                            if ($this->options->JFriends_shuffle == 'on') {
                        		shuffle($friends);
	                        }
                            foreach ($friends as $item) : ?>
                                <li class="joe_detail__friends-item">
                                    <a class="contain" href="<?php echo $item['url']; ?>" target="_blank" rel="noopener noreferrer" style="background: <?php echo $friends_color[mt_rand(0, count($friends_color) - 1)] ?>">
                                        <span class="title"><?php echo $item['name']; ?></span>
                                        <div class="content">
                                            <div class="desc"><?php echo $item['desc']; ?></div>
                                            <img width="40" height="40" class="avatar lazyload" src="<?php joe\getAvatarLazyload(); ?>" data-src="<?php echo $item['avatar']; ?>" alt="<?php echo $item['name']; ?>" />
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php $this->need('module/FriendsSubmit.php'); ?>
                    <?php $this->need('module/handle.php'); ?>
                    <?php $this->need('module/copyright.php'); ?>
                </div>
                <?php $this->need('module/comment.php'); ?>
            </div>
            <?php $this->need('module/aside.php'); ?>
        </div>
        <?php $this->need('module/footer.php'); ?>
    </div>
</body>

</html>