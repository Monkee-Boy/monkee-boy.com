<?php $this->tplDisplay("inc_header.php", ['menu'=>'home']); ?>

  <div class="hero-slideshow loading">
    <div class="current"></div>
    <div class="next"></div>
    <ul class="slider-nav menu-lite">
      <li class="home active" data-id="1" data-image="/images/home-monkee.png">
        <a href="#" class="title">Monkee-Boy</a>
        <div class="slide-caption">
          <h1>Solve the Web</h1>
          <?= $this->getSetting('homepage-slider-monkeeboy-text'); ?>
        </div>
      </li>
      <li data-id="2" data-image="/images/home-discover.png">
        <a href="#" class="title">Discover</a>
        <div class="slide-caption"><?= $this->getSetting('homepage-slider-discover-text'); ?></div>
      </li>
      <li data-id="3" data-image="/images/home-create.png">
        <a href="#" class="title">Create</a>
        <div class="slide-caption"><?= $this->getSetting('homepage-slider-create-text'); ?></div>
      </li>
      <li data-id="4" data-image="/images/home-evolve.png">
        <a href="#" class="title">Evolve</a>
        <div class="slide-caption"><?= $this->getSetting('homepage-slider-evolve-text'); ?></div>
      </li>
    </ul>
    <div class="caption landing-caption">
      <span class="caption-title"></span>
      <div class="caption-content">
        <h1>Solve the Web</h1>
        <?= $this->getSetting('homepage-slider-monkeeboy-text'); ?>
      </div>
      <a href="#" class="slide-trigger">Next Slide</a>
    </div>
  </div>

  <?php
  $oPortfolio = $this->loadModel('portfolio');
  $aPortfolio = $oPortfolio->getLatest();
  if(!empty($aPortfolio)):
  ?>
  <div class="home-portfolio">
    <div class="row-flush">
      <div class="half">
        <div class="section-head" data-text-align="left">
          <span class="section-title dots" data-dots="right"><em>featured from the</em><br>portfolio</span>
          <?php if(!empty($aPortfolio['slides'])): ?>
            <?php if(!empty($aPortfolio['slides'][0]['desktop_image_url'])): ?>
              <div class="desktop screen">
                <div class="screen-inner">
                  <img src="<?php echo $aPortfolio['slides'][0]['desktop_image_url']; ?>">
                </div>
              </div>
            <?php elseif(!empty($aPortfolio['slides'][0]['tablet_image_url'])): ?>
              <div class="tablet screen">
                <div class="screen-inner">
                  <img src="<?php echo $aPortfolio['slides'][0]['tablet_image_url']; ?>">
                </div>
              </div>
            <?php elseif(!empty($aPortfolio['slides'][0]['phone_image_url'])): ?>
              <div class="phone screen">
                <div class="screen-inner">
                  <img src="<?php echo $aPortfolio['slides'][0]['phone_image_url']; ?>">
                </div>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <h3><?php echo $aPortfolio['name']; ?></h3>
          <div class="description"><?php echo $aPortfolio['short_description']; ?></div>
          <ul class="menu-lite">
            <li><a href="/the-work/<?php echo $aPortfolio['tag']; ?>/">View project</a></li>
            <li><a href="/the-work/">View full portfolio</a></li>
          </ul>
          <ul class="service-icons text-hover">
            <?php foreach($aPortfolio['services'] as $aService) { ?>
            <li><span class="<?php echo $aService['tag']; ?> service-icon"><i></i></span><span class="hover"><?php echo $aService['name']; ?></span></li>
            <?php } ?>
          </ul>
        </div>
      </div><!-- /.half -->
    </div>
  </div><!-- /.home-portfolio -->
  <?php endif; ?>

  <div class="panel-wide home-marketing" data-panel-style="callout">
    <div class="row-flush">
      <div class="item-callout">
        <h3 data-text-align="center">Almost 20 years of getting results</h3>

        <p><em>You don't need us to tell you that the web changes constantly. What you need is an experienced team to keep up with that change and get results by:</em></p>

        <ul class="list-style-alt">
          <li>Elevating identity with stunning creative assets.</li>
          <li>Increasing website traffic and brand awareness.</li>
          <li>Converting website visitors into loyal customers.</li>
          <li>Establishing leadership with valuable content.</li>
        </ul>
      </div>

      <div class="item-panel">
        <div class="number">
          <span class="arrow"></span><span class="value">99<span class="metric">%</span></span>
        </div>

        <h3>Increase in mobile visits year over year</h3>
        <p>The Bullock Texas State History Museum's new website attracted almost double the amount of mobile visitors in Q4 after their new site launched.</p>

        <p class="dots" data-dots="dual"><a href="/the-work/bullock-texas-state-history-museum/" title="View Monkee-Boy's work for The Bullock Texas State History Museum.">View the Project</a></p>


      </div>
    </div>
  </div>

  <div class="panel-wide home-clients" data-panel-color="green" data-panel-style="slash">
    <div class="row section-head" data-text-align="right">
      <span class="section-title full dots" data-dots="left"><em>a few of our</em> successful clients</span>
    </div>

    <div class="row-flush">
      <ul class="featured-clients styleless">
        <?php
        $oClient = $this->loadModel('clients');
        $aClients = $oClient->getClients(false, true, true);

        // Shuffle client array and fetch first 4
        shuffle($aClients);
        $aClients = array_slice($aClients, 0, 4);

        foreach($aClients as $aClient): ?>
          <li><a href="/client-list/" title="<?= $aClient['name'] ?>"><img src="<?= $aClient['logo_svg_url'] ?>" alt="<?= $aClient['name'] ?>"></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="panel-wide home-pulse" data-panel-color="teal">
    <div class="row section-head" data-text-align="left">
      <span class="section-title full dots" data-dots="right"><em>the</em><br />jungle beat</span>
    </div>

    <div class="row-flush">
      <div class="one-third item-panel">
        <?php
        $oNews = $this->loadModel('news');
        $aArticle = $oNews->getLatest();
        ?>
        <h4>Latest News</h4>

        <div class="item-panel-inside" data-text-align="center">
          <figure><span class="home-news-icon"></span></figure>

          <p><a href="<?= $aArticle['url'] ?>" title="<?= $aArticle['title'] ?>"><?= $aArticle['title'] ?> »</a></p>
          <time><?= date("m-d", $aArticle['publish_on']) ?></time>
        </div>
      </div>

      <div class="one-third item-panel alt">
        <h4>Sign Up For Our Newsletter</h4>

        <div class="item-panel-inside form-newsletter" data-text-align="center">
          <form action="/mailchimp-subscribe/" method="post">
            <div class="subscribe-status"></div> <!-- success -->
            <div class="subscribe-error hide">There has been an error subscribing to our newsletter. Please try again later.</div>

            <div class="form-fields">
              <label for="form-email">Enter your email</label>
              <input type="email" name="email" id="form-email">
              <input type="submit" value="Subscribe!">
            </div>
          </form>
        </div>
      </div>

      <div class="one-third item-panel twitter">
        <h4>On Twitter</h4>

        <div class="item-panel-inside">
          <?php
          $oTwitter = $this->loadTwitter();
          if($oTwitter !== false):
            $aStatuses = $oTwitter->get('statuses/user_timeline', array('count'=>1));
            $oStatus = array_shift($aStatuses); ?>
            <img src="<?= str_replace('_normal.png', '_bigger.png', $oStatus->user->profile_image_url) ?>" title="monkeeboy" class="profile_image">
            <div class="user">
              <div class="name"><?= $oStatus->user->name ?></div>
              <a href="<?= $oStatus->user->url ?>" title="@<?= $oStatus->user->screen_name ?>">@<?= $oStatus->user->screen_name ?></a>
            </div>
            <div class="text"><?= twitterlink($oStatus->text) ?></div>
            <div class="bottom">
              <time><a href="https://twitter.com/<?php echo $oStatus->user->screen_name; ?>/statuses/<?php echo $oStatus->id_str; ?>"><?php echo date("j M", strtotime($oStatus->created_at)); ?></a></time>
              <script type="text/javascript" async src="//platform.twitter.com/widgets.js"></script>
              <ul class="actions">
                <li>
                  <a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $oStatus->id_str; ?>" class="favorite">Favorite</a>
                </li>
                <li>
                  <a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $oStatus->id_str; ?>" class="retweet">Retweet</a>
                </li>
                <li>
                  <a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $oStatus->id_str; ?>" class="reply">Reply</a>
                </li>
                <li>
                  <a href="https://twitter.com/intent/follow?screen_name=<?php echo $oStatus->user->screen_name; ?>" class="follow">Follow</a>
                </li>
              </ul>
            </div>
          <?php else: ?>
            Failed...
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php $oBlog = $this->loadModel('posts'); ?>
  <div class="panel-wide home-blog" data-panel-color="light-grey">
    <div class="row section-head" data-text-align="center">
      <span class="section-title dots" data-dots="dual">the latest from the blog</span>
    </div>

    <div class="row-flush">
      <?php
      $aPosts = $oBlog->getPosts(null, false, false, null, null, 3);
      foreach($aPosts as $aPost): ?>
      <div class="one-third post-panel" data-text-align="center">
        <div class="post-panel-inside">
          <a href="<?= $aPost['url'] ?>" title="<?= $aPost['title'] ?>">
            <img src="<?= $aPost['listing_image_url'] ?>" alt="<?= $aPost['title'] ?>">
            <div class="post-title"><h5><?= $aPost['title'] ?></h5><span>Read now &raquo;</span></div>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

<?php $this->tplDisplay("inc_footer.php"); ?>
