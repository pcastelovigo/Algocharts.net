<header class="header">
<div class="header__content">
        <div class="header__logo">
        <a href="index.php?market=<?php echo $market;?>"><span class="logo-text-gradient">
	<?php
	if ($market == "pactfi") { $sitio = "PactFiCharts"; }
	if ($market == "algofi") { $sitio = "AlgoFiCharts"; }
	if ($market == "") { $sitio = "AlgoCharts"; }
	echo $sitio;
	?>
	</span></a></div>

        <div class="header__menu">
        <ul class="header__nav">
        <li class="header__nav-item"><a href="https://algocharts.net<?php echo $_SERVER['SCRIPT_NAME']."?market=&asset_in=".$assets[0]."&asset_out=".$assets[1]."&chart=".$chart; ?>" class="header__nav-link">Tinyman</a></li>
        <li class="header__nav-item"><a href="https://algocharts.net<?php echo $_SERVER['SCRIPT_NAME']."?market=pactfi&asset_in=".$assets[0]."&asset_out=".$assets[1]."&chart=".$chart; ?>" class="header__nav-link">Pact.fi</a></li>
        <li class="header__nav-item"><a href="https://algocharts.net<?php echo $_SERVER['SCRIPT_NAME']."?market=algofi&asset_in=".$assets[0]."&asset_out=".$assets[1]."&chart=".$chart; ?>" class="header__nav-link">AlgoFi</a></li>
        <li class="header__nav-item"><a href="https://algocharts.net/portfolio.php" class="header__nav-link">Portfolio calculator</a></li>

        </ul>
        </div>

        <div class="header__actions">
                <div class="header__action header__action--search">
                </div>
                <div class="header__action header__action--profile">
                <a class="header__algorand-btn header__algorand-btn--verified" role="button" id="dropdownMenuProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="img/algologo.png" alt="Algorand Logo">
                <div>
                        <p>1 Algo:</p>
                        <span><?php echo sprintf("%.3f", precio_algo())." USD" ?></span></div>
                        </a>
                </div>
        </div>

<button class="header__btn" type="button"><span></span><span></span><span></span></button>
</div>
</header>
