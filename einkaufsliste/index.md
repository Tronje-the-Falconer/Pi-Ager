---
layout: default
title: Einkaufsliste
---

!!!!!!!!!!!!!!!!!!!!!!!!!!!! Achtung Liste ist aktuell in Bearbeitung !!!!!!!!!!!!!!!!!!!!!!!!!!


# Einkaufsliste

Es handelt sich hier nur um Vorschläge, die Komponennten können durch eigene, ähnliche Positionen ausgetauscht werden. 
(Die meisten Links sind bei Reichelt.de, Voelkner.de und Amazon.de um Versandkosten zu minimieren)

Wenn jemand fehlerhafte Links entdeckt oder bessere Komponenten vorschlagen möchte, bitte im Forum [GSV Pi-Ager](https://www.grillsportverein.de/forum/threads/pi-ager-reifeschranksteuerung-mittels-raspberry-pi.273805/) melden. 

***

### Steuerung

#### Raspberry Pi


* Aktuelle Softeareversion: 3.2.2 Bugfix1 
* [Pi zero W](https://shop.pimoroni.de/products/raspberry-pi-zero-w) Dieses ist die empfohlene Variante da die Platine dafür ausgelegt ist und man meistens auch das integrierte Wlan Modul nutzen kann.
* !!! Achtung keinen Zero WH kaufen da diese Variannte die Stiftleiste schon draufgelötet hat !!!
* Alternative 1: 
  * [Pi zero](http://www.whereismypizero.com/) Kann auch genutzt werden, dann braucht man aber ein entsprechendes [Wlan  Modul](https://www.reichelt.de/WLAN-Adapter/EDIMAX-EW-7811UN/3/index.html?&ARTICLE=99944) mit [Adapterkabel](https://www.amazon.de/gp/product/B002P4TO3I/ref=oh_aui_detailpage_o08_s00?ie=UTF8&psc=1) (siehe auch unten)
* weitere Alternativen:  
 'Sollte man eines der folgenden Raspberry Pi Modelle einsetzen, so muss man ein entsprechende [Jumperkabel](https://www.reichelt.de/Weiteres-Zubehoer/DEBO-KABELSET2/3/index.html?ACTION=3&GROUPID=6671&ARTICLE=176618&OFFSET=16&SID=92WGXOUawQATIAAEvA6EI49c1a6b34c1caf234fea075f5de4093c&LANGUAGE=EN) nutzen je nach dem auch ein [Wlan  Modul](https://www.reichelt.de/WLAN-Adapter/EDIMAX-EW-7811UN/3/index.html?&ARTICLE=99944) mit [Adapterkabel](https://www.amazon.de/gp/product/B002P4TO3I/ref=oh_aui_detailpage_o08_s00?ie=UTF8&psc=1)
  * [Raspberry Pi 2 ](https://www.voelkner.de/products/776427/Raspberry-Pi-2-Model-B-1-GB-ohne-Betriebssystem.html?frm=ffs__Raspberry)
  * [Pi 3B](https://www.voelkner.de/products/882046/Raspberry-Pi-3-Model-B-1-GB-ohne-Betriebssystem.html)
  * [Pi 3B+](https://www.amazon.de/Raspberry-Model-Mainboard-MicroSD-Speicherkartenslot/dp/B00LPESRUK)
  * [Pi 3A+](https://www.reichelt.de/raspberry-pi-3-a-4x-1-4-ghz-512-mb-ram-wlan-bt-raspberry-pi-3a-p243791.html?PROVID=2788&gclid=EAIaIQobChMI2f_aj_ym3wIVSZnVCh2OVgd8EAQYAiABEgIuw_D_BwE&&r=1)
* [microSDHC Karte](https://www.reichelt.de/microsdhc-speicherkarte-16gb-sandisk-ultra-sdsquar016ggn6ma-p214843.html?r=1) (min. 8GB)
* [USB Netzteil 5V / mind. 4A z.B.](https://www.reichelt.de/Netzteile-Festspannung/MW-GST40A05/3/index.html?ACTION=3&LA=2&ARTICLE=171043&GROUPID=4950&artnr=MW+GST40A05&SEARCH=%252A) (Reichelt)

#### Pi-Ager Platine (Hw. Version 2.5)

Die Steuerplatine für den Reifeschrank kann man über das GSV Forum beziehen.
Die Platine ist grundsätzlich als unbestückt Platine erhältlich. Für Leute die etwas Probleme mit dem Löten haben, ist es meistens möglich auch eine bestückte Version zu bekommen, da dieses Projekt ein Hobby ist und wir alle dieses Hobby in unserer Freizeit betreiben kann es bei der bestückten Version zu entsprechenden Wartezeiten kommen.

* [Verkaufsportal Pi-Ager Platine](https://www.grillsportverein.de/forum/threads/verkaufsportal-pi-ager-reifeschrank-platine.288119/) 
Zusätzlich zur Steuerplatine benötigt man noch ein [Relaisboard](#relaisboard) => Details sind auf dieser Seite weiter unten zu finden.
Wer also selbst löten kann, baucht folgende Komponenten:
* Bauteile Pi-Ager Platine 2.5 [Reichelt Einkaufsliste](https://www.reichelt.de/my/1647051) (Reichelt EKL) 
=> hier sind alle Bauteile für die Platine die bei Reichelt erhältlich sind zusammengefasst.

  * Allgemeine Komponennten für die Pi-Ager Platine: (die folgende Liste mit den einzelen Komponennten muss für die 2.5 er Version noch aktualisiert werden, der Link zu Reichelt ist aber aktuell auf die 2,5 Version abgestimmt.)
  
    * 2 x JUMPER, schw. m. Grifflasche - [JUMPER 2,54 SW ](https://www.reichelt.de/kurzschlussbruecke-schw-m-grifflasche-jumper-2-54gl-sw-p9019.html?) (Reichelt EKL)
    * 2 x Stiftleisten 2,54 mm, 1X36, gerade- [MPE 087-1-036](https://www.reichelt.de/stiftleisten-2-54-mm-1x36-gerade-mpe-087-1-036-p119890.html?) (Reichelt EKL)
    * 1 x Stiftleisten 2,54 mm, 2X20, gerade- [MPE 087-2-040](https://www.reichelt.de/stiftleisten-2-54-mm-2x20-gerade-mpe-087-2-040-p119900.html?&trstct=pos_1) (Reichelt EKL)
    * 2 x Buchsenleisten 2,54 mm, 1X04, gerade - [MPE 094-1-004](https://www.reichelt.de/buchsenleisten-2-54-mm-1x04-gerade-mpe-094-1-004-p119913.html?) (Reichelt EKL)
    * 2 x Buchsenleisten 2,54 mm, 1X06, gerade - [MPE 094-1-006](https://www.reichelt.de/buchsenleisten-2-54-mm-1x06-gerade-mpe-094-1-006-p119915.html?) (Reichelt EKL)   
    * 1 x Buchsenleisten 2,54 mm, 1X10, gerade - [MPE 094-1-008](https://www.reichelt.de/buchsenleisten-2-54-mm-1x08-gerade-mpe-094-1-008-p119917.html?r=1) (Reichelt EKL)
    * 2 x Buchsenl. 1x20 gerade für Raspberry Zero [BL 1X20G8 2,54](https://www.reichelt.de/20pol-buchsenleiste-gerade-rm-2-54-h-8-5mm-bl-1x20g8-2-54-p51827.html?)  (Reichelt EKL)
    
    * Option - Pufferung Spannungversorgung Sensor (auf Hauptplatine):
    * 1 x SMD Kondenator G1206 - 100µF 6,3V [X5R-G1206 100/6](https://www.reichelt.de/smd-vielschichtkondensator-g1206-100-f-6-3v-x5r-g1206-100-6-p89744.html?) (Reichelt EKL)
    
  * Anschluss Komponenten für die Pi-Ager Platine (empfohlene Variante) Alternative siehe untere Auflistung Steckerklemmen (siehe Anmerkung 1) 
  
    * 8 x Lötbare Schraubklemme - 2-pol, RM 5,08 mm, 90° - [RND 205-00232](https://www.reichelt.de/RND-connect/RND-205-00232/3/index.html?ACTION=3&LA=5&ARTICLE=170277&GROUPID=7552&artnr=RND+205-00232) (Reichelt EKL)
    * 3 x Lötbare Schraubklemme - 3-pol, RM 5,08 mm, 90° - [RND 205-00233](https://www.reichelt.de/RND-connect/RND-205-00233/3/index.html?ACTION=3&LA=5&ARTICLE=170278&GROUPID=7552&artnr=RND+205-00233) (Reichelt EKL)
    
    
  * SMD Bauteile für den Hardware Alarm
  
    * 1 x BC 848A Transistor SMD NPN -  [BC 848B SMD](https://www.reichelt.de/BC-Transistoren/BC-848B-SMD/3/index.html?ACTION=3&LA=5&ARTICLE=18565&GROUPID=7206&artnr=BC+848B+SMD) (Reichelt EKL)
    * 1 x SMD-Widerstand 0805 3,3kOhm 1,0% - [RND 0805 1 3,3K](https://www.reichelt.de/SMD-0805-von-1-bis-910-kOhm/RND-0805-1-3-3K/3/index.html?ACTION=3&LA=5&ARTICLE=183240&GROUPID=7971&artnr=RND+0805+1+3%2C3K) (Reichelt EKL)
    * 1 x Summer -  [SUMMER TDB 05](https://www.reichelt.de/Signalakustik/SUMMER-TDB-05/3/index.html?ACTION=3&LA=5&ARTICLE=35918&GROUPID=6560&artnr=SUMMER+TDB+05) (Reichelt EKL)
    
  * SMD Bauteile für zusätzliche Temperaturmessung
     * 1 x Spannungsregler 3.3V -  [LP 2985 IM5-3,3 ](https://www.reichelt.de/ICs-LMC-LS-/LP-2985-IM5-3-3/3/index.html?ACTION=3&LA=5&ARTICLE=109425&GROUPID=5467&artnr=LP+2985+IM5-3%2C3) (Reichelt EKL)
     * 1 x 12-bit A/D Converter -  [MCP 3204-CI/SL](https://www.reichelt.de/ICs-MCP-3-5-/MCP-3204-CI-SL/3/index.html?ACTION=3&LA=5&ARTICLE=90078&GROUPID=5472&artnr=MCP+3204-CI%2FSL) (Reichelt EKL) 
     * 4 x SMD-Widerstand 0805 47kOhm 0,1% - [SPR-0805 47,0K](https://www.reichelt.de/SMD-0805-von-1-bis-910-kOhm/SPR-0805-47-0K/3/index.html?ACTION=3&LA=5&ARTICLE=123351&GROUPID=7971&artnr=SPR-0805+47%2C0K) (Reichelt EKL)
     * 4 x SMD-Widerstand 0805 1kOhm 1,0% - [RND 0805 1,0K](https://www.reichelt.de/SMD-0805-von-1-bis-910-kOhm/RND-0805-1-1-0K/3/index.html?ACTION=3&LA=5&ARTICLE=183228&GROUPID=7971&artnr=RND+0805+1+1%2C0K) (Reichelt EKL)
     * 1 x SMD-Kerko 0805 10nF - [KEM C0G0805 10N](https://www.reichelt.de/vielschicht-kerko-10nf-50v-125-c-kem-c0g0805-10n-p207052.html?&trstct=pos_1) (Reichelt EKL)
     * 6 x SMD-Kerko 0805 100nF - [KEM X7R0805 100N](https://www.reichelt.de/vielschicht-kerko-100nf-50v-125-c-kem-x7r0805-100n-p207073.html?&GROUP=B3517&START=0&SORT=artnr&OFFSET=16) (Reichelt EKL)
     * 2 x SMD-Kerko 0805 10µF - [KEM X5R0805 10U ](https://www.reichelt.de/Vielschicht-SMD-G0805/KEM-X5R0805-10U/3/index.html?ACTION=3&LA=5&ARTICLE=207089&GROUPID=8048&artnr=KEM+X5R0805+10U) (Reichelt EKL)
     * 1 x SMD-Widerstand 0805 0 Ohm 5% - [RND 0805 0](https://www.reichelt.de/smd-widerstand-0805-0-ohm-125-mw-5-rnd-0805-0-p183146.html?) (Reichelt EKL)     
     * (*2) Die Klinkenbuchsen: siehe Punkt "Komponenten die bei Reichelt nicht erhältlich sind"
     
  * Bauteile für den Digitaleingang
     * 1 x Widerstand 1,00K-  [METALL 1,00K](https://www.reichelt.de/0-6W-1-1-00-k-Ohm-9-76-k-Ohm/METALL-1-00K/3/index.html?ACTION=3&LA=5&ARTICLE=11403&GROUPID=3078&artnr=METALL+1%2C00K) (Reichelt EKL)
     * 1 x Widerstand 10,0K-  [METALL 10,0K](https://www.reichelt.de/0-6W-1-10-0-k-Ohm-95-3-k-Ohm/METALL-10-0K/3/index.html?ACTION=3&LA=5&ARTICLE=11449&GROUPID=3079&artnr=METALL+10%2C0K) (Reichelt EKL)
   
   * SMD Bauteile für das USV Modul
     * 1 x MOSFET, N-CH, 50V, 0,22A, 0,36W, SOT-23 - [BSS 138 SMD](https://www.reichelt.de/mosfet-n-ch-50v-0-22a-0-36w-sot-23-bss-138-smd-p41437.html?) (Reichelt EKL)
     * 2 x SMD-Widerstand, 0805, 10 kOhm, 125 mW, 1%- [RND 0805 1 10K ](https://www.reichelt.de/smd-widerstand-0805-10-kohm-125-mw-1-rnd-0805-1-10k-p183251.html?) (Reichelt EKL)
     * 1 x Widerstand 10,0K-  [METALL 10,0K](https://www.reichelt.de/widerstand-metallschicht-10-0-kohm-0207-0-6-w-1-metall-10-0k-p11449.html?) (Reichelt EKL)
     * 1 x Widerstand 6,80K-  [METALL 6,80K](https://www.reichelt.de/widerstand-metallschicht-6-8-kohm-0207-0-6-w-0-1-mpr-6-80k-p13032.html?) (Reichelt EKL)
    
   * Bauteile für die Verbindung der Pi-Ager Platine und Relaisboard: 
     * 2 x Buchsenl. 20pol gewinkelt für Verbindung mit Relais Platine [BL 1X20W8 2,54 ](https://www.reichelt.de/20pol-buchsenl-gewinkelt-rm-2-54-h-8-5mm-bl-1x20w8-2-54-p51847.html?) (Reichelt EKL)
     
   * zusätzliche Bauteile für die Waagen: 
     * SMD Kondenator G1206 - 100µF 6,3V [X5R-G1206 100/6](https://www.reichelt.de/smd-vielschichtkondensator-g1206-100-f-6-3v-x5r-g1206-100-6-p89744.html?) (Reichelt EKL)
 

 * (2) Anmerkung
   * Komponenten die bei Reichelt nicht erhältlich sind:
    * Die 2,5mm SMD Klinkenbuchsen sind bei Reichelt leider nicht lieferbar, hier zwei Bezugsquellen:
       * 3 x 2,5 mm Klinkenbuchse (bevorzugte Variante) - [SJ 2523](https://www.mouser.de/ProductDetail/CUI/SJ-2523-SMT-TR/?qs=WyjlAZoYn50TRxpi%2fhdHvw==) (Mouser)
       * 1 x 3,5 mm Klinkenbuchse (bevorzugte Variante) - [SJ 3523](https://www.mouser.de/ProductDetail/CUI-Devices/SJ-3523-SMT-TR?qs=%2Fha2pyFadug%2FV73yDUYRuD0IFnHG0voBw8%252BIF5qrvSfPH1qlnUiXig%3D%3D) (Mouser)
       * 2 x 2A Sicherung [MINISMDC200F-2 ](https://www.mouser.de/ProductDetail/Littelfuse/MINISMDC200F-2?qs=sGAEpiMZZMsxR%252bBXi4wRUNXkmM90UIcXt9OjDxSw2bA%3d) (Mouser)
       * 1 x 1,1A Sicherung [MINISMDC100F-2 ](https://www.mouser.de/ProductDetail/Littelfuse/MINISMDC100F-2?qs=sGAEpiMZZMsxR%252bBXi4wRUNXkmM90UIcXjShDHv3PYgE%3d) (Mouser)
       * Die Sicherungen findet man auch im China (ALiExpress)


 * (1) Anmerkung
 
   * Alternativ zu den Schraubklemmen kann man Steckerklemmen nutzen:
    * INFO: Wenn man öfters z.B. sie Sensoren abklemmen möchte kann man Steckerklemmen einsetzen. Um aber mögliche Kontaktprobleme zu vermeiden ist es sinnvoller doch die Schraubklemmen aus dem Warenkorb zu nutzen.
      * 3 x Wannenstecker für AKL 249 2-pol RM5,08 - [AKL 230-02](https://m.reichelt.de/wannenstecker-fuer-akl-249-2-pol-rm5-08-akl-230-02-p36701.html?) (Reichelt - nicht im Warenkorb enthalten)
      * 1 x Wannenstecker für AKL 249 4-pol RM5,08 - [AKL 230-04](https://www.reichelt.de/Wannenstecker/AKL-230-04/3/index.html?ACTION=3&LA=2&ARTICLE=36703&GROUPID=7544&artnr=AKL+230-04&SEARCH=%252A) (Reichelt - nicht im Warenkorb enthalten)
       * 3 x Wannenstecker für AKL 249 5-pol RM5,08 - [AKL 230-05](https://m.reichelt.de/wannenstecker-fuer-akl-249-5-pol-rm5-08-akl-230-05-p36705.html?&trstct=pos_0) (Reichelt - nicht im Warenkorb enthalten) 
       * 3 x Anschlussklemmensystem 2-pol RM5,08 - [AKL 249-02](https://www.reichelt.de/Steckbare-Anschlussklemmen/AKL-249-02/3/index.html?ACTION=3&LA=517&ARTICLE=36686&GROUPID=7543&trstct=lsbght_sldr::36693) (Reichelt - nicht im Warenkorb enthalten)
       * 1 x Anschlussklemmensystem 4-pol RM5,08 - [AKL 249-04](https://www.reichelt.de/Steckbare-Anschlussklemmen/AKL-249-04/3/index.html?ACTION=3&LA=517&ARTICLE=36688&GROUPID=7543&trstct=lsbght_sldr::36703) (Reichelt - nicht im Warenkorb enthalten)
       * 3 x Anschlussklemmensystem 5-pol RM5,08 - [AKL 249-05](https://www.reichelt.de/Steckbare-Anschlussklemmen/AKL-249-05/3/index.html?ACTION=3&LA=517&ARTICLE=36689&GROUPID=7543&trstct=lsbght_sldr::36696) (Reichelt - nicht im Warenkorb enthalten) 


#### Waagen Module
 
* [HX711 Modul für Wägezellenunterstützung](https://www.amazon.de/dp/B01I37IWYM/ref=cm_sw_r_wa_api_8ol5zb1ZZFX0R)

#### USV Modul 
Es besteht die Option ein USV (unterbrechungsfreie Spannungsversorgung) Modul einzusetzen, damit bei einem möglichen Spannungsausfall ein Alarm ausgelöst, oder z.B. eine Nachricht verschickt werden kann. 
!!! Achtung !!! Da das USV Modul nur einen begrenzten Strom liefern kann, muss man entsprehend vorsichtig sein, wenn man weitere Komponennten direkt an den Raspberry anschließt. Otipmal für den Einsatz eines USV Moduls ist die Verwendung des Raspberry Zero da dieser eine geringere Strohmaufnahme hat.

* USV Modul 
[Adafruit PowerBoost 1000C](https://www.exp-tech.de/module/stromspannung/6462/adafruit-powerboost-1000-charger-rechargeable-5v-lipo-usb-boost-at-1a-1000c?gclid=EAIaIQobChMIlN7npKWq3wIVA6aaCh1jEAeYEAQYBSABEgKsaPD_BwE)
oder
[Adafruit PowerBoost 1000C](https://eckstein-shop.de/Adafruit-PowerBoost-1000-Charger-Rechargeable-5V-Lipo-USB-Boost-1A-1000C)
 
 Hier findet man weitere Informationen, die beim Einsatzt des Moduls beachtet werden müssen: [ADAFRUIT](https://www.adafruit.com/product/2465)

#### Relaisboard

* 1 x 8-Kanal Relais Modul Optokoppler 5VDC 230V  [Relaisboard](https://www.amazon.de/dp/B01I41B8H0/ref=cm_sw_r_cp_api_i_fMhqCbT1K5E3Qhttps://www.amazon.de/dp/B01I41B8H0/ref=cm_sw_r_cp_api_i_fMhqCbT1K5E3Q) (Amazon)
Alternativ koennte auch das hier das richtige sein: [Relaisboard](https://www.amazon.de/AZDelivery-8-Relais-Optokoppler-Low-Level-Trigger-Arduino/dp/B07CT7SLYQ/ref=pd_sbs_147_4/261-9898526-7646407?_encoding=UTF8&pd_rd_i=B07CT7SLYQ&pd_rd_r=0603e1ce-1a59-11e9-bd3a-1b03e59c87b4&pd_rd_w=38MyK&pd_rd_wg=IHj3s&pf_rd_p=51bcaa00-4765-4e8f-a690-5db3c9ed1b31&pf_rd_r=CBYWN36N3REPD3TBDSEJ&psc=1&refRID=CBYWN36N3REPD3TBDSEJ) (Amazon)

Dieses Board das hier angeboten wird, passte bisher (speziell was die Printstiftleisten angeht) immer perfekt zur Steuerplatine und wird innerhalb 1-3 Tage geliefert. Ob dann das Board, dass dann tataechlich geliefert wird wirklich koennen wir leider nicht garantieren. 
* Da die originalen Relais von dem verlinktem Relaisboard in der Vergangenheit einigen Usern Probleme verursacht haben, haben wir mal gesucht und folgendes gefunden. Diese Relais sind laut Datenblatt primärseitig gleich und können auch von den Maßen her die originalen Relais auf der Platine ersetzen. Betrachtet man die Kontakte sind diese viel besser und können also einen höheren Strom schalten, daher hoffen wir die Probleme zumindest bei den kleine Kompressoren zu minimieren. Bei größeren Leistungen muss man z.B. ein entsprechendes Schütz einsetzen.
* 8 x Printrelais 5V Spule - 17/20A Schaltstrom TYP: BRF-SS-105D oder BRF-SS-105D [8 Printrelais](https://www.voelkner.de/products/895637/AFE-Printrelais-5-V-DC-17-A-1-Wechsler-BRF-SS-105D-1-St..html) (Voelkner)
Leider sind beide Typen bei Völkner und Conrad oft ausverkaut... vielicht kriegt man sie auch in China...einfach mal versuchen.
* ACHTUNG es gibt unterschiedliche Boards, das richtige (bei dem genug Plaz ist um die Relais auszutauschen= hat die Maße: 138x55x17 mm. Die mit den Maßen 134x52x17 sind nicht fuer den Realaistausch geeigent.
***
* Der Einsatz eines entsprechenden Schützes in der Kompressoransteuerung ist wohl die sicherste Variannte da es Schütze gibt die für die entsprechende Gebrauchskategorie gebaut wurden. 
Hier ein mögliches Schütz dass man einsetzen könnte (ob es zu eurem Kompressor passt sollte euer Elektriker des Vertrauens leicht beurteilen können)
[Schütz](https://www.voelkner.de/products/1326874/Finder-22.32.0.230.4520-Schuetz-1-Schliesser-1-Oeffner-230-V-DC-230-V-AC-25A-1St..html?ref=51&offer=64f5fd9278547c3f91da0c59797b0153&gclid=CjwKCAjwrvv3BRAJEiwAhwOdM-jWpyaZCpXtuMSwrtjDqcGbzkXXkVIMp_6GBph53KxGhR3LgRa1fhoCLq8QAvD_BwE)

### Hardware

* Kühlschrank (am schönsten natürlich mit Glasscheibentür)

#### Webcam

* Webcam [Webcam](https://www.amazon.de/gp/product/B00LLWASXK/ref=oh_aui_detailpage_o03_s00?ie=UTF8&psc=1) (Amazon)
  * oder eine von Raspberry Pi unterstützte: https://elinux.org/RPi_USB_Webcams

#### Wägezellen

* Wägezelle 20 kg Edelstahl, stabil und laut Hersteller wasserdicht, S-Form. (gute Loesung in dieser Preiskattegorie) [Wägezelle Edelstahl 20KG China](https://www.amazon.de/gp/product/B073NFB644/ref=oh_aui_detailpage_o02_s00?ie=UTF8&psc=1) (Amazon)
* Wägezelle 20kg (sind nicht so stabil aber sehr preiswert...)[Wägezelle 20KG China](https://www.amazon.de/gp/product/B00IHUJQ4Q/ref=oh_aui_detailpage_o09_s00?ie=UTF8&psc=1) (Amazon)
* Wägezelle 10kg (sind nicht so stabil aber sehr preiswert...)[Wägezelle 10KG China](https://www.amazon.de/dp/B00J4FW7YO/ref=cm_sw_r_wa_api_fnl5zb1ZZFX0R) (Amazon)
 
#### Sensor

* Temperatur-Feuchtigkeitssensor (Sensirion SHT3X) 
    * [Sensirion SHT3x Variannte 1 - blau](https://www.reichelt.de/entwicklerboards-temperatur-und-feuchtigkeitssensor-sht31-d-debo-temp-hum-p235485.html?PROVID=2788&gclid=Cj0KCQjwk8b7BRCaARIsAARRTL6k6-cO-SSTQE_zNjwCXU2QgORjfiIkMufwe5C0RatENoLGv4lxxjkaApNyEALw_wcB) (Reichelt)
    * [Sensirion SHT3x Variannte 2 - violett](https://www.amazon.de/ACAMPTAR-Temperatur-Feuchtigkeitssensor-Breakout-Wettermodul/dp/B07QS6CXGX/ref=asc_df_B07QS6CXGX/?tag=googshopde-21&linkCode=df0&hvadid=447530082948&hvpos=&hvnetw=g&hvrand=4683590616312955685&hvpone=&hvptwo=&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=9044713&hvtargid=pla-955407784206&psc=1&th=1&psc=1) (Amazon)
    * 3D Gehäuse [STL Dateien Gehäuse](https://www.thingiverse.com/thing:4227612) (Thigniverse)
* SHT 85 [SHT85](https://www.mouser.de/ProductDetail/Sensirion/SHT85?qs=byeeYqUIh0OExKWW1Eu%252BLQ%3D%3D&gclid=Cj0KCQjwk8b7BRCaARIsAARRTL6jkEu0gmwoSHlY3vacO1ct5fAOmUNdLMsSyIZksNNTLvZpIhAjp8MaAkNqEALw_wcB) (mouser)
* SHT75 - nicht mehr lieferbar
* Folgende Sensoren kann man im Moment noch nutzen werden aber in einer der nächsten Release nicht mehr unterstützt:
    * [DHT22](https://www.amazon.de/gp/product/B01M9H3GID/ref=oh_aui_detailpage_o00_s00?ie=UTF8&psc=1) (Amazon)
    * [DHT11](https://www.amazon.de/gp/product/B01M7VQLWD/ref=oh_aui_detailpage_o02_s00?ie=UTF8&psc=1) (Amazon)
* Sensorgehäuse für SHT85 [Gehäuse SHT75](https://www.voelkner.de/products/25666/Sensorgehaeuse-Schwarz.html) (Voelkner)
* abgeschirmtes Kabel passend für das Sensorgehäuse [ Sensorkabel](https://www.voelkner.de/products/36610/Kabel-6-Polig-Geschirmt-3m.html) (Voelkner)
* Buchse für SHT75 um den Sensor steckbar zu machen [Buschse RM 1,27](https://www.voelkner.de/products/297503/Fischer-Elektronik-Buchsenleiste-RM-1-27-mm-Inhalt-1-St..html?ref=43&products_model=D23000&gclid=EAIaIQobChMIm4_MyemC2AIVxTLTCh3AEA3UEAQYASABEgIIw_D_BwE) (Voelkner)

#### Temperatursensoren Fleisch 

(Achtung: Funktion ist z.Zt. noch nicht implementiert)

* Für diese Funktion ist die Unterstützung von 1000 K NTC Sensoren geplant. 
  * 1000K NTC Sensor [1000K NTC](https://www.wlanthermostuff.de/store/) (Wlanthermostuff)

#### Be- und Entlüftung

*  Lüfter mit Jalousie  [Wand- und Deckenlüfter 100mm](https://www.voelkner.de/products/98640/Wand-und-Deckenluefter-Eco-Matic-100mm.html) (Voelkner)
* Aussengitter [Aussengitter 100mm mit Fliegennetz](https://www.voelkner.de/products/162455/Aussengitter-100-M-Fliegennetz-Ws.html) (Voelkner)
* Ablufthaube [Ablufthaube 100mm mit Fallklappe](https://www.voelkner.de/products/162465/Ablufthaube-100-Weiss.html) (Voelkner)
* Jalousie Klappe [Jalousieklappe 100mm](https://www.voelkner.de/products/162471/Jalousieklappe-Nw-100-Weiss.html) (Voelkner)
* Optional:
  * [Umluftunterstützung](https://www.amazon.de/gp/product/B01I6H5HHO/ref=oh_aui_detailpage_o07_s00?ie=UTF8&psc=1) (Amazon)

#### Befeuchtung

* Luftbefeuchter Beispiel: [Luftbefeuchter](https://www.amazon.de/gp/product/B0047O0LKE/ref=oh_aui_detailpage_o00_s00?ie=UTF8&psc=1) (Amazon)

#### Heizung

* Heizkabel Beispiel: [Heizkabel](https://www.amazon.de/Trixie-76082-Heizkabel-50-00/dp/B003087SHC/ref=sr_1_3?ie=UTF8&qid=1487192650&sr=8-3&keywords=heizkabel) (Amazon)

#### Entfeuchtung

* Entfeuchter Beispiel gibt ews noch nicht da noch keiner einen im Einsatz somit keine Erfahrungswerte vorliegen.

#### Display

* Display [2.8" Nextion TFT HMI Display ENHANCED](http://www.komputer.de/zen/index.php?main_page=product_info&cPath=30&products_id=384) 

#### Diverses
* Stromkabel
* [Steckdosen](https://www.voelkner.de/products/8066/Schutzsteckdose-IP44-Feuchtraum-LED-2-fach-16-A-230-V.html#tech-data) (Voelkner)
* [Kabelabzweigkasten für Raspberry Pi und Relaisboard](https://www.amazon.de/gp/product/B001JMOOXW/ref=oh_aui_detailpage_o04_s00?ie=UTF8&psc=1)
* geschirmtes CAT-Kabel

#### Spezialwerkzeug
* [Lochsäge 100mm](https://www.voelkner.de/products/432935/Wolfcraft-Lochsaege-100-mm-5493000-1-St..html) (Voelkner)

#### Alternative Bezugsquellen
* [2 Micro USB-Kabel](https://www.voelkner.de/products/545808/USB-2.0-Anschlusskabel-1x-USB-2.0-Stecker-A-1x-USB-2.0-Stecker-Micro-B-1-m-Schwarz-UL-zertifizi.html) (Voelkner)
* [Adapter von USB micro B auf USB A Buchse](https://www.amazon.de/gp/product/B002P4TO3I/ref=oh_aui_detailpage_o08_s00?ie=UTF8&psc=1) (sofern Pi Zero oder Pi Zero W gewählt wurde) (Amazon)
  * [USB WLAN-Stick](https://www.voelkner.de/products/213356/EDIMAX-WLAN-Stick-N150-Nano-Ew-7811un.html?frm=ffs__EDIMAX%20EW-7811UN) (Voelkner)
  * [pi Buchsenleiste](https://www.voelkner.de/products/770924/Econ-Connect-Buchsenleiste-Standard-Anzahl-Reihen-2-Polzahl-je-Reihe-20-BL20-2G8-1-St..html)
  * [Metallschicht-Widerstand 10kOhm](https://www.voelkner.de/products/876301/Yageo-Metallschicht-Widerstand-10-k-axioal-bedrahtet-0207-0.6-W-MF0207FTE52-10K-1-St..html)
  * [Metallschicht-Widerstand 1kOhm](https://www.voelkner.de/products/876338/Yageo-Metallschicht-Widerstand-1-k-axial-bedrahtet-0207-0.6-W-MF0207FTE52-1K-1-St..html)
  * 3x [Steckerbuchse 2 polig](https://www.voelkner.de/products/46498/Grundgehaeuse-Mstb-2-5-2-G-5-08.html)
  * 1x [Steckerbuchse 4 polig](https://www.voelkner.de/products/46510/Grundgehaeuse-Mstb-2-5-4-G-5-08.html)
  * 3x [Steckerbuchse 5 polig](https://www.voelkner.de/products/46517/Grundgehaeuse-Mstb-2-5-5-G-5-08.html)
  * 3x [Stecker 2 polig](https://www.voelkner.de/products/46503/Steckerteil-Mstb-2-5-2-St-5-08.html)
  * 1x [Stecker 4 polig](https://www.voelkner.de/products/46516/Steckerteil-Mstb-2-5-4-St-5-08.html)
  * 3x [Stecker 5 polig](https://www.voelkner.de/products/46523/Steckerteil-Mstb-2-5-5-St-5-08.html)
  * Anstelle der Buchsen-Stecker-Lösung können auch alternativ entsprechende Schraubklemmblöcke verwendet werden:
    * 3x [Schraubklemmblock 3 polig](https://www.voelkner.de/products/813542/Degson-Schraubklemmblock-2.08-mm-Polzahl-3-DG127-5.08-03P-14-00AH-Gruen-1-St..html)
    * 8x [Schraubklemmblock 2 polig](https://www.voelkner.de/products/813540/Degson-Schraubklemmblock-2.08-mm-Polzahl-2-DG127-5.08-02P-14-00AH-Gruen-1-St..html)
  * [Stiftleiste 2 reihig](https://www.voelkner.de/products/187967/Stiftleiste-2x36-polig-Gerade-Rm-2-54.html)
  * [Stiftleiste 1 reihig](https://www.voelkner.de/products/215043/Stiftleiste-Gerade-Rm-2-54-1-X-40-Pol.html)
  * 2x [90° Buchsenleiste 10 fach](https://www.voelkner.de/products/955090/Connfly-Buchsenleiste-Standard-Anzahl-Reihen-1-Polzahl-je-Reihe-10-1498286-1-St..html)
  * 2x [90° Buchsenleiste 3 fach](https://www.voelkner.de/products/955462/Connfly-Buchsenleiste-Standard-Anzahl-Reihen-1-Polzahl-je-Reihe-3-1498281-1-St..html)
  * [Buchsenleiste 1 reihig](https://www.voelkner.de/products/215681/Buchsenleiste-Gerade-Rm-2-54-1x40-Pol.html)
  * [2 Micro USB-Kabel](https://www.voelkner.de/products/545808/USB-2.0-Anschlusskabel-1x-USB-2.0-Stecker-A-1x-USB-2.0-Stecker-Micro-B-1-m-Schwarz-UL-zertifizi.html)
