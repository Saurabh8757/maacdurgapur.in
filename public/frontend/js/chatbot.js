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

  var welcome = '\u{1F44B} <strong>Welcome to AKSHA International School of Design & Technology!</strong><br><br>I am <strong>AKSHA AI</strong> \u2014 your personal admission counselor powered by <strong>MAAC Durgapur</strong> \u{1F393}<br><br>I can help you with:<br><ul><li>\u{1F3A8} Choosing the right creative or tech course</li><li>\u{1F4BC} Career guidance & salary insights</li><li>\u{1F4CB} Admission process & fees</li><li>\u{1F916} AI, Animation, Gaming, Robotics & more</li></ul><br>What would you like to explore today?';
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
  var lower = text.toLowerCase();
  if (lower.indexOf('course') !== -1 || lower.indexOf('fee') !== -1) {
    return 'We offer industry-leading courses in:\n<ul><li><strong>Animation & VFX</strong> \u2014 Maya, Houdini, Nuke</li><li><strong>UI/UX Design</strong> \u2014 Figma, Adobe XD</li><li><strong>Graphic Design</strong> \u2014 Photoshop, Illustrator</li><li><strong>Game Development</strong> \u2014 Unreal Engine, Unity</li><li><strong>AI & Robotics</strong> \u2014 Python, Machine Learning</li><li><strong>Digital Marketing</strong> \u2014 SEO, Social Media</li></ul><br>Want to <button class="qr-btn" onclick="showLeadForm()">\u{1F4CB} Book Free Counselling</button> for detailed fee structure?';
  }
  if (lower.indexOf('career') !== -1 || lower.indexOf('guidance') !== -1) {
    return '<strong>\u{1F680} Career Opportunities</strong><br><br>MAAC Durgapur graduates work at top studios like:<br><ul><li>Netflix, DNEG, Prime Video</li><li>Ubisoft, Rockstar Games, EA</li><li>Tata Elxsi, Pogo</li></ul><br>Average starting salary: <strong>\u20B93.5 - 6 LPA</strong><br><br>Ready to start? <button class="qr-btn" onclick="showLeadForm()">\u{1F4CB} Book Counselling Now</button>';
  }
  if (lower.indexOf('placement') !== -1) {
    return '<strong>\u{1F4BC} Placement Support</strong><br><br>We provide <strong>100% placement assistance</strong> including:<br><ul><li>Portfolio building</li><li>Mock interviews</li><li>Industry visits</li><li>Direct recruitment drives</li></ul><br>Our students are placed at India\'s top creative studios!';
  }
  if (lower.indexOf('admission') !== -1 || lower.indexOf('fee') !== -1) {
    return '<strong>\u{1F393} Admission & Fees</strong><br><br>We offer flexible payment options and scholarships for meritorious students. <br><br>Click below to get detailed fee info: <button class="qr-btn" onclick="showLeadForm()">\u{1F4CB} Get Fee Details</button>';
  }
  return 'That\'s a great question! <br><br>Let me connect you with our admission counselor who can provide detailed information.<br><br>In the meantime, you can <button class="qr-btn" onclick="showLeadForm()">\u{1F4CB} Book Free Counselling</button> or ask me about:<br><ul><li>\u{1F3A8} Courses we offer</li><li>\u{1F4BC} Career guidance</li><li>\u{1F4CB} Fees & admission</li><li>\u{1F4C8} Placement info</li></ul>';
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


