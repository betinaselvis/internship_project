$(function () {
  const token = localStorage.getItem('auth_token');

  if (!token) {
    window.location.href = 'login.html';
    return;
  }

  // Fetch profile data
  $.ajax({
    url: 'api/profile_get.php',
    method: 'POST',
    data: { token: token },
    dataType: 'json'
  }).done(function (resp) {
    if (!resp.success) {
      alert('Failed to load profile');
      localStorage.clear();
      window.location.href = 'login.html';
      return;
    }

    const user = resp.user;
    const education = resp.education || [];
    const skills = resp.skills || [];
    const experience = resp.experience || [];
    const certifications = resp.certifications || [];

    // Populate profile view
    $('#fullNameView').text((user.first_name || '') + ' ' + (user.last_name || '') || '—');
    $('#firstNameView').text(user.first_name || '—');
    $('#lastNameView').text(user.last_name || '—');
    $('#headlineView').text(user.headline || '—');
    $('#locationView').text(user.location || '—');
    $('#aboutView').text(user.about || '—');
    $('#emailView').text(user.email || '—');
    $('#phoneView').text(user.phone || '—');
    $('#genderView').text(user.gender || '—');
    $('#dobView').text(user.dob || '—');
    $('#altEmailView').text(user.alternate_email || '—');
    $('#addressView').text(user.address || '—');

    // Calculate age
    if (user.dob) {
      const birthDate = new Date(user.dob);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      $('#ageView').text(age + ' years');
      $('#ageViewStat').text(age);
    } else {
      $('#ageView').text('—');
      $('#ageViewStat').text('—');
    }

    // Update quick stats
    $('#experienceCountStat').text(experience.length);
    $('#skillsCountStat').text(skills.length);
    $('#educationCountStat').text(education.length);

    // Social links
    if (user.website) {
      $('#websiteView').attr('href', user.website).text(user.website);
    } else {
      $('#websiteView').text('—');
    }
    if (user.github_url) {
      $('#githubView').attr('href', user.github_url).text(user.github_url);
    } else {
      $('#githubView').text('—');
    }
    if (user.linkedin_url) {
      $('#linkedinView').attr('href', user.linkedin_url).text(user.linkedin_url);
    } else {
      $('#linkedinView').text('—');
    }

    // Populate edit form
    $('#editFirstName').val(user.first_name || '');
    $('#editLastName').val(user.last_name || '');
    $('#editHeadline').val(user.headline || '');
    $('#editAbout').val(user.about || '');
    $('#editLocation').val(user.location || '');
    $('#editGender').val(user.gender || '');
    $('#editDob').val(user.dob || '');
    $('#editPhone').val(user.phone || '');
    $('#editEmail').val(user.email || '');
    $('#editAltEmail').val(user.alternate_email || '');
    $('#editAddress').val(user.address || '');
    $('#editWebsite').val(user.website || '');
    $('#editGithub').val(user.github_url || '');
    $('#editLinkedin').val(user.linkedin_url || '');

    // Populate education section
    if (education.length > 0) {
      $('#educationSection').show();
      education.forEach(edu => {
        const html = `
          <div class="item-card">
            <div class="item-title">${edu.degree || '—'} in ${edu.field_of_study || '—'}</div>
            <div class="item-subtitle">${edu.institution_name || '—'} (${edu.start_year || '—'} - ${edu.end_year || '—'})</div>
            ${edu.cgpa ? `<p><small>CGPA: ${edu.cgpa}</small></p>` : ''}
            ${edu.description ? `<div class="item-description">${edu.description}</div>` : ''}
          </div>
        `;
        $('#educationList').append(html);
      });
    }

    // Populate skills section
    if (skills.length > 0) {
      $('#skillsSection').show();
      let skillsHTML = '<div class="skill-container">';
      skills.forEach(skill => {
        skillsHTML += `<span class="skill-tag">${skill.skill_name} <span class="skill-level">${skill.skill_level}</span></span>`;
      });
      skillsHTML += '</div>';
      $('#skillsList').html(skillsHTML);
    }

    // Populate experience section
    if (experience.length > 0) {
      $('#experienceSection').show();
      experience.forEach(exp => {
        const html = `
          <div class="item-card">
            <div class="item-title">${exp.title || '—'}</div>
            <div class="item-subtitle">${exp.organization || '—'} (${exp.start_date || '—'} - ${exp.end_date || '—'})</div>
            ${exp.tech_stack ? `<p><small><strong>Tech Stack:</strong> ${exp.tech_stack}</small></p>` : ''}
            ${exp.description ? `<div class="item-description">${exp.description}</div>` : ''}
          </div>
        `;
        $('#experienceList').append(html);
      });
    }

    // Populate certifications section
    if (certifications.length > 0) {
      $('#certificationsSection').show();
      certifications.forEach(cert => {
        const html = `
          <div class="item-card">
            <div class="item-title">${cert.certification_name || '—'}</div>
            <div class="item-subtitle">Issued by ${cert.issued_by || '—'} on ${cert.issue_date || '—'}</div>
            ${cert.certificate_url ? `<p><a href="${cert.certificate_url}" target="_blank">View Certificate</a></p>` : ''}
          </div>
        `;
        $('#certificationsList').append(html);
      });
    }

    // Store data in window for edit mode
    window.currentEducation = education;
    window.currentSkills = skills;
    window.currentExperience = experience;
    window.currentCertifications = certifications;

    // Populate edit form with existing data
    $('#editFirstName').val(user.first_name || '');
    $('#editLastName').val(user.last_name || '');
    $('#editHeadline').val(user.headline || '');
    $('#editAbout').val(user.about || '');
    $('#editLocation').val(user.location || '');
    $('#editPhone').val(user.phone || '');
    $('#editGender').val(user.gender || '');
    $('#editDob').val(user.dob || '');
    $('#editAltEmail').val(user.alternate_email || '');
    $('#editAddress').val(user.address || '');
    $('#editWebsite').val(user.website || '');
    $('#editGithub').val(user.github_url || '');
    $('#editLinkedin').val(user.linkedin_url || '');

    // Render existing entries in edit mode
    renderExistingEducation(education);
    renderExistingSkills(skills);
    renderExistingExperience(experience);

  }).fail(function () {
    alert('Network error loading profile');
  });

  // Helper function to render existing education
  function renderExistingEducation(education) {
    const container = $('#educationEntries');
    container.empty();
    education.forEach((edu, index) => {
      const html = `
        <div class="entry-item">
          <div class="entry-item-header">
            <div style="flex: 1;">
              <div class="entry-item-title">${edu.degree || 'N/A'} in ${edu.field_of_study || 'N/A'}</div>
              <div class="entry-item-subtitle">${edu.institution_name || 'N/A'} (${edu.start_year || '—'} - ${edu.end_year || '—'})</div>
              ${edu.cgpa ? `<div class="entry-item-detail">CGPA: ${edu.cgpa}</div>` : ''}
              ${edu.description ? `<div class="entry-item-detail">${edu.description}</div>` : ''}
            </div>
            <button type="button" class="remove-entry-btn remove-education" data-id="${edu.id || index}">
              <i class="fas fa-trash"></i> Remove
            </button>
          </div>
        </div>
      `;
      container.append(html);
    });
  }

  // Helper function to render existing skills
  function renderExistingSkills(skills) {
    const container = $('#skillEntries');
    container.empty();
    skills.forEach((skill, index) => {
      const html = `
        <div class="skill-item">
          ${skill.skill_name} <span class="skill-level">${skill.skill_level}</span>
          <button type="button" class="remove-entry-btn remove-skill" data-id="${skill.id || index}" style="display: none;"></button>
        </div>
      `;
      container.append(html);
    });
  }

  // Helper function to render existing experience
  function renderExistingExperience(experience) {
    const container = $('#experienceEntries');
    container.empty();
    experience.forEach((exp, index) => {
      const html = `
        <div class="entry-item">
          <div class="entry-item-header">
            <div style="flex: 1;">
              <div class="entry-item-title">${exp.title || 'N/A'}</div>
              <div class="entry-item-subtitle">${exp.organization || 'N/A'} (${exp.start_date || '—'} - ${exp.end_date || '—'})</div>
              ${exp.tech_stack ? `<div class="entry-item-detail"><strong>Tech Stack:</strong> ${exp.tech_stack}</div>` : ''}
              ${exp.description ? `<div class="entry-item-detail">${exp.description}</div>` : ''}
            </div>
            <button type="button" class="remove-entry-btn remove-experience" data-id="${exp.id || index}">
              <i class="fas fa-trash"></i> Remove
            </button>
          </div>
        </div>
      `;
      container.append(html);
    });
  }

  // Event handler for Add Education button
  $('#addEducationBtn').on('click', function (e) {
    e.preventDefault();
    
    const institution = $('#newEduInstitution').val().trim();
    const degree = $('#newEduDegree').val().trim();
    const field = $('#newEduField').val().trim();
    const startYear = $('#newEduStartYear').val();
    const endYear = $('#newEduEndYear').val();
    const cgpa = $('#newEduCGPA').val();
    const description = $('#newEduDescription').val().trim();

    if (!institution || !degree || !field || !startYear) {
      alert('Please fill in all required fields for education');
      return;
    }

    const token = localStorage.getItem('auth_token');
    $.ajax({
      url: 'api/education_add.php',
      method: 'POST',
      data: {
        token: token,
        institution_name: institution,
        degree: degree,
        field_of_study: field,
        start_year: startYear,
        end_year: endYear,
        cgpa: cgpa,
        description: description
      },
      dataType: 'json'
    }).done(function (resp) {
      if (resp.success) {
        // Clear form
        $('#newEduInstitution').val('');
        $('#newEduDegree').val('');
        $('#newEduField').val('');
        $('#newEduStartYear').val('');
        $('#newEduEndYear').val('');
        $('#newEduCGPA').val('');
        $('#newEduDescription').val('');
        
        // Re-fetch profile
        location.reload();
      } else {
        alert(resp.message || 'Failed to add education');
      }
    }).fail(function () {
      alert('Network error');
    });
  });

  // Event handler for Add Skill button
  $('#addSkillBtn').on('click', function (e) {
    e.preventDefault();
    
    const skillName = $('#newSkillName').val().trim();
    const skillLevel = $('#newSkillLevel').val();

    if (!skillName || !skillLevel) {
      alert('Please fill in all required fields for skill');
      return;
    }

    const token = localStorage.getItem('auth_token');
    $.ajax({
      url: 'api/skills_add.php',
      method: 'POST',
      data: {
        token: token,
        skill_name: skillName,
        skill_level: skillLevel
      },
      dataType: 'json'
    }).done(function (resp) {
      if (resp.success) {
        // Clear form
        $('#newSkillName').val('');
        $('#newSkillLevel').val('');
        
        // Re-fetch profile
        location.reload();
      } else {
        alert(resp.message || 'Failed to add skill');
      }
    }).fail(function () {
      alert('Network error');
    });
  });

  // Event handler for Add Experience button
  $('#addExperienceBtn').on('click', function (e) {
    e.preventDefault();
    
    const title = $('#newExpTitle').val().trim();
    const organization = $('#newExpOrganization').val().trim();
    const startDate = $('#newExpStartDate').val();
    const endDate = $('#newExpEndDate').val();
    const techStack = $('#newExpTechStack').val().trim();
    const description = $('#newExpDescription').val().trim();

    if (!title || !organization || !startDate || !description) {
      alert('Please fill in all required fields for experience');
      return;
    }

    const token = localStorage.getItem('auth_token');
    $.ajax({
      url: 'api/experience_add.php',
      method: 'POST',
      data: {
        token: token,
        title: title,
        organization: organization,
        start_date: startDate,
        end_date: endDate,
        description: description,
        tech_stack: techStack
      },
      dataType: 'json'
    }).done(function (resp) {
      if (resp.success) {
        // Clear form
        $('#newExpTitle').val('');
        $('#newExpOrganization').val('');
        $('#newExpStartDate').val('');
        $('#newExpEndDate').val('');
        $('#newExpTechStack').val('');
        $('#newExpDescription').val('');
        
        // Re-fetch profile
        location.reload();
      } else {
        alert(resp.message || 'Failed to add experience');
      }
    }).fail(function () {
      alert('Network error');
    });
  });

  // Event handlers for removing entries
  $(document).on('click', '.remove-education', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this education entry?')) {
      const id = $(this).data('id');
      const token = localStorage.getItem('auth_token');
      $.ajax({
        url: 'api/education_delete.php',
        method: 'POST',
        data: { token: token, id: id },
        dataType: 'json'
      }).done(function (resp) {
        if (resp.success) {
          location.reload();
        } else {
          alert(resp.message || 'Failed to delete education');
        }
      }).fail(function () {
        alert('Network error');
      });
    }
  });

  $(document).on('click', '.remove-skill', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this skill?')) {
      const id = $(this).data('id');
      const token = localStorage.getItem('auth_token');
      $.ajax({
        url: 'api/skills_delete.php',
        method: 'POST',
        data: { token: token, id: id },
        dataType: 'json'
      }).done(function (resp) {
        if (resp.success) {
          location.reload();
        } else {
          alert(resp.message || 'Failed to delete skill');
        }
      }).fail(function () {
        alert('Network error');
      });
    }
  });

  $(document).on('click', '.remove-experience', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this experience entry?')) {
      const id = $(this).data('id');
      const token = localStorage.getItem('auth_token');
      $.ajax({
        url: 'api/experience_delete.php',
        method: 'POST',
        data: { token: token, id: id },
        dataType: 'json'
      }).done(function (resp) {
        if (resp.success) {
          location.reload();
        } else {
          alert(resp.message || 'Failed to delete experience');
        }
      }).fail(function () {
        alert('Network error');
      });
    }
  });


  // Edit Profile Button
  $('#editProfileBtn').on('click', function () {
    $('#profileViewMode').hide();
    $('#profileEditMode').show();
  });

  // Cancel Edit
  $('#cancelEditBtn').on('click', function () {
    $('#profileEditMode').hide();
    $('#profileViewMode').show();
  });

  // Save Profile
  $('#profileForm').on('submit', function (e) {
    e.preventDefault();

    const data = {
      token: token,
      first_name: $('#editFirstName').val().trim(),
      last_name: $('#editLastName').val().trim(),
      headline: $('#editHeadline').val().trim(),
      about: $('#editAbout').val().trim(),
      location: $('#editLocation').val().trim(),
      gender: $('#editGender').val(),
      dob: $('#editDob').val(),
      phone: $('#editPhone').val().trim(),
      alternate_email: $('#editAltEmail').val().trim(),
      address: $('#editAddress').val().trim(),
      website: $('#editWebsite').val().trim(),
      github_url: $('#editGithub').val().trim(),
      linkedin_url: $('#editLinkedin').val().trim()
    };

    $.ajax({
      url: 'api/profile_update.php',
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function (resp) {
      if (resp.success) {
        alert('Profile updated successfully');
        location.reload();
      } else {
        alert(resp.message || 'Failed to update profile');
      }
    }).fail(function () {
      alert('Network error');
    });
  });

  // Logout
  $('#logoutBtn').on('click', function () {
    $.ajax({
      url: 'api/logout.php',
      method: 'POST',
      data: { token: token }
    }).always(function () {
      localStorage.clear();
      window.location.href = 'login.html';
    });
  });
});


