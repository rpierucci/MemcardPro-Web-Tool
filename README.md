# MemcardPro-Web-Tool
Tool used to facilitate viewing downloaded Playstation 1 MemcardPro Memory card files using the SD Card or its built in FTP function. Useful for Steam Deck Transfer from an original PS1

![alt text](https://pieruccidev.com/img/MemcardPro-Web-Tool.png)


<b>Requirements</b>

PHP 7.4.8<br>
PS1 Memory Card files downloaded into the MemoryCards folder stored in the format as the example Xenogears card

<b>Purpose</b>

I enjoy playing my PS1 games on my original playstation. I currently have an X-Station ODE installed and picked up a Memcard Pro from 8bitMods. With this, I can have an SD card full
of all of the saves with ease of swapping, but what if I wanted to put these saves on my Steam Deck running an emulator for on the go? This tool is for that purpose. It is currently work
in progress and incomplete, but I will be adding a few extra features to finish off my vision in the near future.

<b>Usage</b>

Using this application is simple. Install it in a place with a PHP server running (I developed this on 7.4.8). Launch your browser of choice and navigate to the index.php of the
site. On the web page, click on the link that says "Refresh Game List". This will then scan your /MemoryCards directory for folders within it in the playstation game code format
(Ex: SLUS-86033, SCUS-94103, SLPM-86003 etc). Within those folders you should have memory cards in a format such as GAME_CODE-1.mcd, GAME_CODE-2.mcd, etc. This is the normal
generation method for memory cards created within the MemcardPro when selecting a game through X-Station by default.

Once you scan your cards, click on one and click "Read Selected Game Cards". The page will refresh showing your 1-8 mcd files and any save data present. You can then click on the
card FileName link to download the card to the device you are viewing the web page on. 

This seems pointless but I wanted an easy way to transfer files to my Steam Deck. As such, run this application on your windows desktop. Use your Steam Deck in desktop mode
to navigate to the web server running on your LAN and download memory card files quickly to your Steam Deck. At this point, just use Duckstation or your choice of emulator to
import the memory card file into it and now your save is on your Steam Deck faster than using some sort of syncing application.

<b>Future</b>
At this time MemcardPro Web Tool only supports downloading of files. I plan to add a way to "upload" your .mcd file back to the web server where you can then ftp or copy the
mcd contents back to your MemcardPro and resume play on your original PS1. Otherwise I may mess around adding visual enhancements (animated memory card icons, UI
resembling an original PS1, etc) and anything that I find useful when thinking about purposes for this software other than what I use it for.

<b>Special Thanks/Resource</b>

Thanks to shendoXT, author of the MemcardRex program for his code. Without it I would never have had as great of understanding of Memory Card Icons and storage than without
his source Code. Additionally, https://www.psdevwiki.com/ps3/PS1_Savedata was very helpful in figuring out some stuff.
