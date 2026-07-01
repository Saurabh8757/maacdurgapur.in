// ─────────────────────────────────────────────
// CONFIG
// ─────────────────────────────────────────────
var SYSTEM_PROMPT = 'You are AKSHA AI, the official intelligent virtual admission counselor and student success assistant for AKSHA International School of Design & Technology and MAAC Durgapur. Your role is Admission Counselor, Career Advisor, Course Consultant, AI & Technology Mentor, Creative Industry Guide. Institute: AKSHA International School of Design & Technology, Powered By MAAC Durgapur & SPACE-E-FIC Robotics & AI, Location: Near City Center, Durgapur, West Bengal. COURSES: Graphic Design, UI/UX, Animation, VFX, Gaming, AI, Robotics, Digital Marketing, Motion Graphics, Video Editing. SOFTWARE: Photoshop, Illustrator, Figma, Blender, Maya, Houdini, Unreal Engine, Unity, Premiere Pro, After Effects. GOALS: Help choose course, convert to admission, capture leads, explain careers/salary. STYLE: Professional, friendly, motivational, human-like. Use HTML formatting with strong, ul/li, br. Always guide toward admission or counselling.';

var QUICK_REPLIES_INITIAL = ['Explore Courses 🎨', 'Fees & Admission 💰', 'Career Guidance 🚀', 'Book Counselling 📅', 'Placement Info 💼'];

// ─────────────────────────────────────────────
// STATE
// ─────────────────────────────────────────────
var chatHistory = [];
var isTyping = false;
var leadFormShown = false;
var chatOpened = false;

// ─────────────────────────────────────────────
// TOGGLE
// ─────────────────────────────────────────────
function toggleChat() {

  var win = document.getElementById('aksha-window');
  var btn = document.getElementById('aksha-launcher');
  if (!win || !btn) return;
  if (win.classList.contains('visible')) {
    win.classList.remove('visible');
    btn.classList.remove('open');

  } else {
    win.classList.add('visible');
    btn.classList.add('open');

    if (!chatOpened) { chatOpened = true; setTimeout(showWelcome, 300); }
  }
}

function closeChat() {

  var win = document.getElementById('aksha-window');
  var btn = document.getElementById('aksha-launcher');
  if (!win || !btn) return;
  win.classList.remove('visible');
  btn.classList.remove('open');
}

// ─────────────────────────────────────────────
// WELCOME
// ─────────────────────────────────────────────
function showWelcome() {

  var welcome = '\u{1F44B} <strong>Hello! Welcome to MAAC Durgapur & AKSHA!</strong><br><br>Main <strong>AKSHA AI</strong> hoon \u2014 aapka personal career guide. \u{1F60E}<br>Agar aap Animation, VFX, Gaming ya Tech field me apna solid career banana chahte ho, toh aap bilkul sahi jagah aaye ho!<br><br>Boliye, main aapki kaise help kar sakti hoon?<br><ul><li>\u{1F3A8} Best course choose karne me?</li><li>\u{1F4BC} Placement aur salary details chahiye?</li><li>\u{1F4CB} Admission aur fees structure janna hai?</li></ul><br>Neeche diye gaye options me se select karein ya direct message type karein! \u{1F447}';
  appendBotMessage(welcome, QUICK_REPLIES_INITIAL);
}

// ─────────────────────────────────────────────
// MESSAGES
// ─────────────────────────────────────────────
function appendBotMessage(html, quickReplies) {
  quickReplies = quickReplies || [];
  var messages = document.getElementById('chat-messages');
  var row = document.createElement('div');
  row.className = 'msg-row bot';
  var avatar = document.createElement('div');
  avatar.className = 'msg-avatar';
  avatar.textContent = 'AI';
  var bubble = document.createElement('div');
  bubble.className = 'msg-bubble';
  bubble.innerHTML = html;
  if (quickReplies.length > 0) {
    var qrDiv = document.createElement('div');
    qrDiv.className = 'quick-replies';
    quickReplies.forEach(function(qr) {
      var btn = document.createElement('button');
      btn.className = 'qr-btn';
      btn.textContent = qr;
      btn.onclick = function() { sendUserText(qr); };
      qrDiv.appendChild(btn);
    });
    bubble.appendChild(qrDiv);
  }
  row.appendChild(avatar);
  row.appendChild(bubble);
  messages.appendChild(row);
  scrollToBottom();
}

function appendUserMessage(text) {
  var messages = document.getElementById('chat-messages');
  var row = document.createElement('div');
  row.className = 'msg-row user';
  var avatar = document.createElement('div');
  avatar.className = 'msg-avatar';
  avatar.textContent = '\u{1F464}';
  var bubble = document.createElement('div');
  bubble.className = 'msg-bubble';
  bubble.textContent = text;
  row.appendChild(bubble);
  row.appendChild(avatar);
  messages.appendChild(row);
  scrollToBottom();
}

function showTyping() {
  var messages = document.getElementById('chat-messages');
  var row = document.createElement('div');
  row.className = 'msg-row bot';
  row.id = 'typing-indicator';
  var avatar = document.createElement('div');
  avatar.className = 'msg-avatar';
  avatar.textContent = 'AI';
  var bubble = document.createElement('div');
  bubble.className = 'msg-bubble';
  bubble.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
  row.appendChild(avatar);
  row.appendChild(bubble);
  messages.appendChild(row);
  scrollToBottom();
}

function removeTyping() {
  var el = document.getElementById('typing-indicator');
  if (el) el.remove();
}

function scrollToBottom() {
  var m = document.getElementById('chat-messages');
  setTimeout(function() { m.scrollTop = m.scrollHeight; }, 50);
}

// ─────────────────────────────────────────────
// LEAD FORM — Submits to Laravel backend
// ─────────────────────────────────────────────
function showLeadForm() {
  if (leadFormShown) return;
  leadFormShown = true;
  var messages = document.getElementById('chat-messages');
  var row = document.createElement('div');
  row.className = 'msg-row bot';
  var avatar = document.createElement('div');
  avatar.className = 'msg-avatar';
  avatar.textContent = 'AI';
  var bubble = document.createElement('div');
  bubble.className = 'msg-bubble';
  bubble.innerHTML = '<strong>\u{1F4CB} Book Your Free Counselling Session</strong><br><br>Fill in your details and our team will reach out shortly!';
  var form = document.createElement('div');
  form.className = 'lead-form';
  var courseSelect = document.getElementById('modal-course') || document.getElementById('modal-course_id');
  var courseOptions = courseSelect ? courseSelect.innerHTML : '<option value="" disabled selected>Interested Course</option>';
  
  form.innerHTML = '<input type="text" id="lf-name" placeholder="Your Full Name *" /><div class="lead-error" id="lf-name-error"></div><input type="tel" id="lf-phone" placeholder="Phone Number *" /><div class="lead-error" id="lf-phone-error"></div><input type="text" id="lf-email" placeholder="Email Address" /><div class="lead-error" id="lf-email-error"></div><select id="lf-course">' + courseOptions + '</select><div class="lead-error" id="lf-course-error"></div><button type="button" onclick="submitLead()">\u{1F680} Submit &amp; Book Counselling</button>';
  bubble.appendChild(form);
  row.appendChild(avatar);
  row.appendChild(bubble);
  messages.appendChild(row);
  scrollToBottom();
}

function submitLead() {

  var nameEl = document.getElementById('lf-name');
  var phoneEl = document.getElementById('lf-phone');
  var emailEl = document.getElementById('lf-email');
  var courseEl = document.getElementById('lf-course');
  var submitBtn = document.querySelector('.lead-form button');
  var name = nameEl ? nameEl.value.trim() : '';
  var phone = phoneEl ? phoneEl.value.trim() : '';
  var email = emailEl ? emailEl.value.trim() : '';
  var course = courseEl ? courseEl.value : '';

  // Clear errors
  document.querySelectorAll('.lead-error').forEach(function(el) { el.textContent = ''; });

  // Validate
  var hasError = false;
  if (!name) { document.getElementById('lf-name-error').textContent = 'Please enter your name'; hasError = true; }
  if (!phone) { document.getElementById('lf-phone-error').textContent = 'Please enter phone number'; hasError = true; }
  else if (!/^\d+$/.test(phone)) { document.getElementById('lf-phone-error').textContent = 'Phone must be numeric'; hasError = true; }
  if (!email) { document.getElementById('lf-email-error').textContent = 'Please enter your email'; hasError = true; }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { document.getElementById('lf-email-error').textContent = 'Invalid email format'; hasError = true; }
  if (!course) { document.getElementById('lf-course-error').textContent = 'Please select a course'; hasError = true; }

  if (hasError) return;

  // Disable button & show loading
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
  }

  // Get CSRF token
  var csrfToken = '';
  var meta = document.querySelector('meta[name="csrf-token"]');
  if (meta) csrfToken = meta.getAttribute('content');

  // Submit via AJAX to Laravel route
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/career-counselling', true);
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

  xhr.onload = function() {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = '\u{1F680} Submit & Book Counselling';
    }
    if (xhr.status >= 200 && xhr.status < 300) {
      try {
        var resp = JSON.parse(xhr.responseText);
        if (resp.status === 1) {
          // Success
          var msgDiv = document.querySelector('.lead-form');
          if (msgDiv) {
            msgDiv.innerHTML = '<div class="lead-success"><span class="success-icon">\u2705</span><h4>Thank You! \u{1F389}</h4><p>Your counselling request has been received. Our team will reach out shortly!</p></div>';
          }

        } else if (resp.status === 0 && resp.error) {
          // Server-side validation errors
          if (resp.error.name) document.getElementById('lf-name-error').textContent = resp.error.name[0];
          if (resp.error.phone) document.getElementById('lf-phone-error').textContent = resp.error.phone[0];
          if (resp.error.email) document.getElementById('lf-email-error').textContent = resp.error.email[0];
          if (resp.error.course_id) document.getElementById('lf-course-error').textContent = resp.error.course_id[0];
        }
      } catch(e) {

      }
    } else {

    }
  };

  xhr.onerror = function() {

    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = '\u{1F680} Submit & Book Counselling';
    }
  };

  var brandIdInput = document.querySelector('input[name="brand_id"]');
  var brandId = brandIdInput ? brandIdInput.value : '';

  var payload = JSON.stringify({
    brand_id: brandId,
    name: name,
    phone: phone,
    email: email,
    course_id: course,
    form_type: 'global_modal',
    source_page: 'Global Modal'
  });
  xhr.send(payload);
}

// ─────────────────────────────────────────────
// SEND MESSAGE
// ─────────────────────────────────────────────
function sendUserText(text) {

  if (isTyping) return;
  appendUserMessage(text);
  chatHistory.push({ role: 'user', content: text });

  // Handle quick reply actions
  if (text.indexOf('Book Counselling') !== -1) {
    setTimeout(showLeadForm, 500);
    return;
  }

  // Simulate bot response (AI integration can be added later)
  isTyping = true;
  showTyping();

  setTimeout(function() {
    removeTyping();
    isTyping = false;
    var botReply = getBotReply(text);
    appendBotMessage(botReply);
    chatHistory.push({ role: 'assistant', content: botReply });
  }, 1200);
}

function getBotReply(text) {
  var t = text.toLowerCase();
  
  // 1. GREETINGS
  if (/(hi|hello|hey|namaste|pranam|morning|evening|afternoon|kaise ho|kya haal)/i.test(t)) {
      return 'Hello ji! 🙏 Main AKSHA AI hoon, MAAC Durgapur ki official career expert.😎<br><br>Aapka swagat hai! Boliye, aaj main aapki kis cheez me madad karun? Agar aapko kisi special course ya career scope ke baare me janna hai, toh bejhijhak poochiye!';
  }

  // 2. SPECIFIC COURSES - ANIMATION & VFX
  if (/(animation|vfx|visual effects|3d|maya|blender|nuke|houdini)/i.test(t)) {
      return '🔥 <strong>Animation & VFX toh humara USP hai!</strong><br><br>Hollywood se lekar Bollywood tak, saari badi movies me VFX ka use ho raha hai. Hum aapko <strong>Maya, Nuke, Houdini</strong> jaise industry-standard softwares sikhate hain. Ek dam zero se pro level tak! 🎬<br><br>Kya aap isme apna career banana chahte hain? Agar haan, toh der kis baat ki? <button class="qr-btn" onclick="showLeadForm()">🚀 Free Counselling Book Karein</button>';
  }

  // 3. SPECIFIC COURSES - GAME DESIGN
  if (/(game|gaming|unreal|unity|pubg|bgmi|gamedev)/i.test(t)) {
      return '🎮 <strong>Game Design & Development!</strong> Yeh industry abhi boom par hai!<br><br>Hum aapko <strong>Unreal Engine aur Unity 3D</strong> sikhate hain. Aap khud ke AAA quality games, characters aur environments bana sakte hain. 🕹️ Game studios me freshers ki demand bohot high hai right now.<br><br>Details chahiye? <button class="qr-btn" onclick="showLeadForm()">🎮 Game Design Details</button>';
  }

  // 4. SPECIFIC COURSES - UI/UX & GRAPHICS
  if (/(ui|ux|graphic|design|photoshop|illustrator|figma|web|app)/i.test(t)) {
      return '🎨 <strong>UI/UX & Graphic Design!</strong> Sabse zyada high-paying aur creative field!<br><br>Chahe website ho, mobile app ho ya branding—har jagah designers ki zarurat hai. Hum aapko <strong>Figma, Photoshop, Illustrator</strong> par master banate hain. Ek solid portfolio aapko easily top MNCs me job dila sakta hai! 💸<br><br>Interest hai? <button class="qr-btn" onclick="showLeadForm()">✨ Expert se Discuss Karein</button>';
  }

  // 5. ALL COURSES GENERAL
  if (/(course|program|kya sikhate|kya kya|options|padhai|class)/i.test(t)) {
      return 'Humare paas future-proof aur high-paying careers ke top courses hain: 🏆\n<ul><li>🎬 <strong>Animation & VFX</strong> (Hollywood Level)</li><li>🎨 <strong>UI/UX & Graphic Design</strong> (High Demand)</li><li>🎮 <strong>Game Design & Dev</strong> (Unreal/Unity)</li><li>🤖 <strong>AI & Robotics</strong> (Future Tech)</li><li>📱 <strong>Digital Marketing</strong> (Growth hacking)</li></ul><br>Aapko inme se konsi field sabse zyada exciting lagti hai? Ya main suggest karun? 😉';
  }

  // 6. SALARY & JOBS
  if (/(salary|paisa|package|income|kamai|job|naukri|nokri|placement|scope)/i.test(t)) {
      return '💰 <strong>Jobs & Salary ki baat karein? Ekdum solid scope hai!</strong><br><br>MAAC Durgapur me humara focus sirf sikhane par nahi, aapko <strong>100% Placement</strong> dilane par hota hai. Humare students aaj <strong>Netflix, Rockstar Games, DNEG, Ubisoft</strong> jaisi top companies me kaam kar rahe hain! 🏢<br><br>Ek fresher aaram se <strong>₹3.5 Lakhs se ₹6 Lakhs PA</strong> start kar sakta hai. Aur 2-3 saal ke experience ke baad salary exponentially badhti hai! 🚀<br><br>Apna career plan banana hai? <button class="qr-btn" onclick="showLeadForm()">💼 Talk to Career Expert</button>';
  }

  // 7. FEES & ADMISSION
  if (/(fee|cost|price|kharcha|admission|join|apply|emi|scholarship)/i.test(t)) {
      return '🎓 <strong>Admission & Fees Structure</strong><br><br>Humara manna hai ki talent ko paiso ki wajah se rukna nahi chahiye. Isliye humare paas <strong>Easy EMI Options</strong> aur deserving candidates ke liye special <strong>Scholarships</strong> available hain! 🎉<br><br>Har course ki duration aur fees alag hoti hai. Exact details ke liye main ek expert ko aapse connect karwa deti hoon. <button class="qr-btn" onclick="showLeadForm()">📋 Get Exact Fee Details</button>';
  }

  // 8. LOCATION & CONTACT
  if (/(location|address|kahan|where|contact|phone|number|call)/i.test(t)) {
      return '📍 <strong>Humara Center Kahan Hai?</strong><br><br>Humara state-of-the-art campus <strong>Near City Center, Durgapur, West Bengal</strong> me hai. Yahan aapko premium studios aur high-end systems milenge practice ke liye! 🖥️<br><br>Aap aakar campus visit kar sakte hain ya call arrange karwa sakte hain: <button class="qr-btn" onclick="showLeadForm()">📞 Request a Call Back</button>';
  }

  // 9. FRUSTRATION / ABUSE / NEGATIVE
  if (/(bekar|kharab|faltu|bakwas|gali|stupid|idiot|dumb)/i.test(t)) {
      return 'Mujhe khed hai agar aapko koi pareshani hui hai. 😔 Main ek AI hoon aur lagatar seekh rahi hoon. Meri puri koshish hai aapki best help karne ki. Agar aapko koi serious query hai toh please direct humari team se baat karein: <button class="qr-btn" onclick="showLeadForm()">📞 Request Human Support</button>';
  }

  // 10. DEFAULT / CATCH-ALL
  return 'Sahi kaha aapne! 👍 Par main ek AI assistant hoon toh lagta hai is specific sawal ka mere paas exact jawab abhi nahi hai.<br><br>Lekin tension mat lijiye! Humare senior counselors aapse direct baat karke aapke saare doubts clear kar denge. Ek baar unse discuss karke dekhiye, aapko maza aayega aur career clarity bhi milegi! 💯<br><br><button class="qr-btn" onclick="showLeadForm()">🎯 Book Free Expert Counselling</button>';
}

function sendMessage() {

  var input = document.getElementById('chat-input');
  if (!input) return;
  var text = input.value.trim();
  if (!text) return;
  input.value = '';
  autoResize(input);
  sendUserText(text);
}

function handleKey(event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

function autoResize(el) {
  el.style.height = 'auto';
  el.style.height = el.scrollHeight + 'px';
}


