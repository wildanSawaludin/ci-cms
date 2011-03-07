<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "
";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title><?php if(isset($channel['title'])) echo $channel['title']; ?></title>

    <link><?php if(isset($channel['link'])) echo $channel['link']; ?></link>
	
    <description><?php if(isset($channel['description'])) echo $channel['description']; ?></description>
    <dc:language><?php echo $this->user->lang; ?></dc:language>
    <dc:creator><?php echo $this->system->admin_email; ?></dc:creator>

    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://solaitra.tuxfamily.org/" />

    <?php foreach($items as $item): ?>
    
        <item>
          <title><?php if(isset($item['title'])) echo $item['title']; ?></title>
          <author><?php if(isset($item['author'])) echo $item['author']; ?></author>
          <link><?php if(isset($item['link'])) echo $item['link'] ?></link>
          <guid><?php if(isset($item['guid'])) echo $item['guid'] ?></guid>
          <description> <?php if(isset($item['description'])) echo $item['description'] ?></description>
		  <pubDate><?php if(isset($item['date'])) echo date ('r', $item['date']);?></pubDate>		  
        </item>

    <?php endforeach; ?>
    
    </channel></rss>  