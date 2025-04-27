# Simple text scraper on PHP

### The parser does:
#### 1) Outputs only direct speech (paragraphs <p> starting with an em dash)

![image](https://github.com/user-attachments/assets/1d185a91-2155-46d0-b668-70074681c8af)

##### Output
###### — Папа, как насчет сказки? — спросил Кристофер Робин.
###### — Что насчет сказки? — спросил папа.
###### — Ты не мог бы рассказать Винни-Пуху сказочку? Ему очень хочется!
###### ...

#### 2) Automatically places commas before "a" and "but". Replace three dots with the special ellipsis character.

#### 3) Automatically generate a working table of contents for headings of levels 1-3. A table of contents with indents by heading levels should be displayed under the form. Clicking on a heading in the table of contents jumps to the corresponding heading in the full text. 
#### 4) Remove all types of visual formatting from the original HTML, leaving only functional and structural elements: <H?>, `<P>`, `<div>`, table and link tags. If the text was inside the tag being removed, for example <font>text with font</font>, it should be preserved. All attributes and their values ​​should be removed inside the tags.

### Additional features
#### - if you write in the address bar ****/text.php?preset=1, the form opens with the text of this article https://ru.wikipedia.org/wiki/%D0%9A%D0%B8%D0%BD%D0%BE%D1%80%D0%B8%D0%BD%D1%85%D0%B8
#### - if you write in the address bar ****/text.php?preset=2, the form opens with the text of this article https://www.gazeta.ru/culture/2021/12/16/a_14322589.shtml 
#### - if you write in the address bar ****/text.php?preset=3, the form opens with the text of this article https://mishka-knizhka.ru/skazki-dlay-detey/zarubezhnye-skazochniki/skazki-alana-milna/vinni-puh-i-vse-vse-vse/#glava-pervaya-v-kotoroj-my-znakomimsya-s-vinni-puhom-i-neskolkimi-pchy 

![image](https://github.com/user-attachments/assets/c63158f5-3372-4195-b4f6-03ce3b147e15)

![image](https://github.com/user-attachments/assets/507dd06f-f623-4bcb-a20f-d4702dcaf954)

![image](https://github.com/user-attachments/assets/8ae6efa5-65e6-464b-9bb4-b5f1d48b055a)

![image](https://github.com/user-attachments/assets/385cbd7c-ea1b-4e0b-ab36-5af993e4bf3b)
